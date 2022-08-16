<?php

namespace plugin\webman\gateway;

use App\enum\WebSocketMsgType;
use App\exception\WebSocketAuthenticationException;
use App\exception\WebSocketNoSendException;
use App\model\User;
use App\support\websocket\MessageProcessing;
use GatewayWorker\Lib\Gateway;
use support\Log;
use support\Redis;
use Workerman\Timer;

class Events
{
    public static function onConnect($clientId)
    {
    }

    public static function onWebSocketConnect($clientId, $data)
    {
        $getData = $data['get'];
        $uid = $getData['uid'] ?? null;
        // 重复上线验证
        if (count(Gateway::getClientIdByUid($uid)) > 0) {
            Gateway::sendToClient($clientId, json_encode([
                'msg'  => "当前账号已经在线啦，请换个账号试试！",
                'type' => WebSocketMsgType::ERROR,
            ], JSON_UNESCAPED_UNICODE));
            // 延迟一秒断开链接
            sleep(1);
            return Gateway::closeClient($clientId);
        }
        // 验证 Auth
        try {
            $user = static::checkAuth($clientId, $getData);
        } catch (WebSocketAuthenticationException $exception) {
            Gateway::sendToClient($exception->clientId, json_encode([
                'msg'  => $exception->getMessage(),
                'type' => WebSocketMsgType::AUTH,
            ], JSON_UNESCAPED_UNICODE));
            // 延迟一秒断开链接
            sleep(1);
            return Gateway::closeClient($clientId);
        }
        // 下放聊天记录
        $start = strtotime("-1 hours")."000";
        $end = time()."000";
        $chatRecord = Redis::zrangebyscore("liao:group_{$user['group_id']}", $start, $end);
        websocket_send($user['id'], ['list' => $chatRecord], WebSocketMsgType::INIT);
        // 更新房间信息
        // static::updateRoomInfo();
    }

    public static function onMessage($clientId, $message)
    {
        $data = json_decode($message, true);
        $type = $data['type'] ?? 'no';
        if ($class = config("message-class.{$type}")) {
            try {
                $user = Gateway::getSession($clientId);
                $message = new MessageProcessing(new $class(), $clientId, $data);
                Gateway::sendToGroup($user['group_id'], $message->send());
            } catch (WebSocketNoSendException $exception) {
                // 不做任何操作
            }
        } else {
            Gateway::sendToClient($clientId, "【原样返回】{$message}");
        }
    }

    public static function onClose($clientId)
    {
    }

    /**
     * 身份验证
     * Author 王世杰 [m@wangxiaoda.com]
     * Date 2022/8/15 10:07
     * @param $clientId
     * @param $data
     * @return false|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws \Throwable
     */
    public static function checkAuth($clientId, $data)
    {
        $uid = $data['uid'] ?? null;
        $identity = $data['identity'] ?? null;
        $room = $data['room'] ?? 1;

        throw_if(blank($uid) || blank($identity), new WebSocketAuthenticationException($clientId, "缺少身份验证参数！"));

        $user = User::checkIdentityByUid($uid, $identity);

        throw_if(!$user, new WebSocketAuthenticationException($clientId));

        // 关联uid
        Gateway::bindUid($clientId, $user->id);
        // 加入到房间
        Gateway::joinGroup($clientId, $room);
        // 记录用户信息
        $user->offsetSet('group_id', $room); // 群信息
        $user->offsetSet('client_id', $clientId); // clientId
        Gateway::setSession($clientId, $user->toArray());

        return $user;
    }

    public static function updateRoomInfo()
    {
        websocket_send_group(1, [
            'online'     => Gateway::getAllUidCount(),
            'roomOnline' => Gateway::getAllGroupUidCount()[1] ?? 0,
        ], WebSocketMsgType::ROOM_INFO);
    }
}
