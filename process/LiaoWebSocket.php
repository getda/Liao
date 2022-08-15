<?php
/**
 * Author 王小大 [m@wangxiaoda.com]
 * Date 2022/8/11 9:51
 * Description
 */

namespace process;

use app\exception\WebSocketAuthenticationException;
use app\exception\WebSocketNoSendException;
use app\support\websocket\MessageProcessing;
use Workerman\Connection\TcpConnection;
use Workerman\Timer;

class LiaoWebSocket
{
    const HEARTBEAT_TIME = 25;

    public function onWorkerStart($worker)
    {
        Timer::add(10, function () use ($worker) {
            $time_now = time();
            foreach ($worker->connections as $connection) {
                // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
                if (empty($connection->lastMessageTime)) {
                    $connection->lastMessageTime = $time_now;
                    continue;
                }
                // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
                if ($time_now - $connection->lastMessageTime > static::HEARTBEAT_TIME) {
                    $connection->close();
                }
            }
        });
    }

    public function onConnect(TcpConnection $connection)
    {

    }

    public function onWebSocketConnect(TcpConnection $connection, $http_buffer)
    {

        var_dump(request()->cookie());
    }

    public function onMessage(TcpConnection $connection, $message)
    {
        $connection->lastMessageTime = time();
        $data = json_decode($message, true);
        if (is_null($data) || !isset($data['type'])) {
            return;
        }
        if ($class = config("message-class.{$data['type']}")) {
            try {
                $message = new MessageProcessing(new $class(), $data);
                $connection->send($message->send());
            } catch (WebSocketAuthenticationException $exception) {
                // 权限验证失败
                $this->sendError($connection, $exception->getMessage());
                $connection->close();
            } catch (WebSocketNoSendException $exception) {
                // 不做任何操作
            }
        } else {
            $connection->send($message);
        }
    }

    public function onClose(TcpConnection $connection)
    {

    }

    /**
     * 发送异常消息
     * Author 王小大 [m@wangxiaoda.com]
     * Date 2022/8/13 11:42
     * @param $connection
     * @param $message
     * @return void
     */
    public function sendError($connection, $message)
    {
        $connection->send(json_encode([
            'status' => 401,
            'type'   => 'auth',
            'msg'    => $message,
        ]));
    }
}