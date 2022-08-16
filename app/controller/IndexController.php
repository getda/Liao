<?php

namespace App\controller;

use App\exception\WebSocketAuthenticationException;
use App\model\User;
use App\support\websocket\type\AuthMessage;
use App\validate\UserValidate;
use GatewayWorker\Lib\Gateway;
use support\Redis;
use support\Request;

class IndexController
{
    /**
     * 首页
     * Author 王小大 [m@wangxiaoda.com]
     * DateTime 2022/8/11 0:09
     * @param  Request  $request
     * @return \support\Response
     */
    public function index(Request $request)
    {
        // 是否存在身份 cookie
        $uid = (int) $request->cookie('uid');
        $identityHash = (string) $request->cookie('identity');
        if ($uid && $identityHash) {
            return redirect(route('chatroom'));
        }

        return view('index/index');
    }

    public function join(Request $request)
    {
        $data = $request->post();
        $validate = new UserValidate();
        if (!$validate->check($data)) {
            return send_message($validate->getError());
        }
        $user = User::query()->firstOrCreate(['name' => $data['nickname']], [
            'password' => $data['password'],
        ]);
        if ($user && !$user->checkPassword($data['password'])) {
            return send_message("口令错误啦~");
        }
        // 判断是否在线
        if (Gateway::isUidOnline($user->id)) {
            return send_message("当前账号已经在线啦，请换个账号试试！");
        }
        $userId = $user->id;
        $authKey = $user->authKey();

        return send(['url' => route('chatroom', ['uid' => $userId, 'identity' => $authKey])]);
    }

    /**
     * 聊天界面
     * Author 王小大 [m@wangxiaoda.com]
     * DateTime 2022/8/11 0:09
     * @param  Request  $request
     * @return \support\Response
     */
    public function chatroom(Request $request)
    {
        $uid = (int) $request->cookie('uid') ?: $request->get('uid', '');
        $identity = (string) $request->cookie('identity') ?: $request->get('identity', '');
        // 验证并获取用户信息
        $user = User::checkIdentityByUid($uid, $identity);
        if (!$user) {
            return $this->logout();
        }

        return view(
            'index/chatroom',
            ['chatroomName' => "欢乐聊天室", 'user' => $user->only(['id', 'name', 'created_at'])]
        )->cookie('uid', $uid, User::EXPIRE_AT)
            ->cookie('identity', $identity, User::EXPIRE_AT);
    }

    /**
     * 退出
     * Author 王小大 [m@wangxiaoda.com]
     * DateTime 2022/8/11 0:09
     * @return \support\Response
     */
    public function logout()
    {
        return redirect(route('index'))
            ->cookie('uid', '', 0)
            ->cookie('identity', '', 0);
    }
}
