<?php
/**
 * Author 王世杰 [m@wangxiaoda.com]
 * Date 2022/8/13 11:38
 * Description
 */

namespace App\exception;

class WebSocketAuthException extends \Exception
{
    public function __construct(string $message = "身份验证失败！", int $code = 401, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}