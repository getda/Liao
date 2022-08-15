<?php

namespace App\support\websocket\type;

use App\exception\WebSocketAuthenticationException;
use App\model\User;
use App\support\interface\ChatInterface;

/**
 * Author 王小大 [m@wangxiaoda.com]
 * Date 2022/8/13 11:08
 * Description
 */
class AuthMessage implements ChatInterface
{
    public array $data;

    public function handle()
    {
        $uid = $this->data['uid'] ?? '';
        $identity = $this->data['identity'] ?? '';
        throw new WebSocketAuthenticationException();
        // throw_if(empty($uid) || empty($identity), new WebSocketAuthException("缺少身份验证参数！"));
        // throw_unless(User::checkIdentityByUid($uid, $identity), new WebSocketAuthException());
    }

    public function send(): array
    {
        $this->handle();
    }
}