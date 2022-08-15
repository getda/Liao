<?php

namespace App\support\websocket\type;

use App\support\interface\ChatInterface;

/**
 * Author 王小大 [m@wangxiaoda.com]
 * Date 2022/8/13 11:08
 * Description
 */
class PingMessage implements ChatInterface
{
    public array $data;
    public       $user;

    public function send(): array
    {
        return ['type' => 'pong'];
    }
}