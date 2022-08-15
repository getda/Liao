<?php
/**
 * Author 王小大 [m@wangxiaoda.com]
 * DateTime 2022/8/14 11:04
 * Description 异常处理
 **/

namespace App\exception;

use App\enum\WebSocketMsgType;
use GatewayWorker\Lib\Gateway;
use support\exception\BusinessException;
use Throwable;
use Webman\Exception\ExceptionHandler;
use Webman\Http\Request;
use Webman\Http\Response;

class Handler extends ExceptionHandler
{
    public $dontReport = [
        BusinessException::class,
    ];

    public function report(Throwable $e)
    {
        parent::report($e);
    }

    public function render(Request $request, Throwable $e): Response
    {
        if ($e instanceof WebSocketAuthenticationException) {
            Gateway::sendToClient($e->clientId, json_encode([
                'msg' => "权限验证失败！",
                'status' => 401,
                'auth' => WebSocketMsgType::AUTH
            ], JSON_UNESCAPED_UNICODE));
        }

        return parent::render($request, $e);
    }
}