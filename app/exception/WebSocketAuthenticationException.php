<?php
/**
 * Author 王世杰 [m@wangxiaoda.com]
 * Date 2022/8/13 11:38
 * Description
 */

namespace App\exception;

class WebSocketAuthenticationException extends \Exception
{
    public function __construct(public $clientId, string $message = "身份验证失败！")
    {
        parent::__construct($message);
    }
}