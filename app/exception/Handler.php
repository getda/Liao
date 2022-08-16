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
        var_dump($e);
        parent::report($e);
    }

    public function render(Request $request, Throwable $e): Response
    {
        var_dump($e);
        if ($e instanceof WebSocketAuthenticationException) {
            Gateway::sendToClient($e->clientId, json_encode([
                'msg' => $e->getMessage(),
                'auth' => WebSocketMsgType::AUTH
            ], JSON_UNESCAPED_UNICODE));
            sleep(1);
            Gateway::closeClient($e->clientId);
        }

        return parent::render($request, $e);
    }
}