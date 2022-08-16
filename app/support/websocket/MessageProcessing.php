<?php

namespace App\support\websocket;

use App\support\interface\ChatInterface;
use GatewayWorker\Lib\Gateway;

/**
 * Author 王小大 [m@wangxiaoda.com]
 * Date 2022/8/13 10:55
 * Description
 */
class MessageProcessing
{
    public function __construct(public ChatInterface $message, public $clientId, public $data)
    {
        $this->message->data = $data;
        $this->message->user = Gateway::getSession($clientId);
        // 处理方法
        if (method_exists($this->message, 'handler')) {
            $this->message->handler();
        }
    }

    public function send(): string
    {
        return json_encode(array_merge([
            'type' => $this->data['type'],
        ], $this->message->send()), JSON_UNESCAPED_UNICODE);
    }
}