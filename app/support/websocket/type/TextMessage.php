<?php

namespace App\support\websocket\type;

use App\enum\WebSocketMsgType;
use App\exception\WebSocketNoSendException;
use App\model\User;
use App\support\interface\ChatInterface;
use support\Redis;

/**
 * Author 王小大 [m@wangxiaoda.com]
 * Date 2022/8/13 11:08
 * Description
 */
class TextMessage implements ChatInterface
{
    public array $data;
    public array $user;
    public array $message;

    public function send(): array
    {
        return $this->message;
    }

    public function handler()
    {
        $this->storage();
    }

    /**
     * 写入 redis
     * Author 王小大 [m@wangxiaoda.com]
     * DateTime 2022/8/14 15:54
     * @return void
     * @throws WebSocketNoSendException
     */
    public function storage()
    {
        $content = $this->data['content'];
        if (!$content) {
            websocket_send($this->user['id'], '消息内容不能为空！', WebSocketMsgType::ERROR);
            websocket_no_send();
        }
        $this->message = [
            'uid'       => $this->user['id'],
            'nickname'  => $this->user['name'],
            'content'   => $content,
            'send_time' => date('Y-m-d H:i:s'),
        ];
        // 写入消息
        Redis::zAdd("liao:group_{$this->user['group_id']}", millisecond(),
            json_encode($this->message, JSON_UNESCAPED_UNICODE));
    }
}