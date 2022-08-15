<?php
namespace App\support\interface;

/**
 * Author 王小大 [m@wangxiaoda.com]
 * Date 2022/8/13 10:56
 * Description
 */
interface ChatInterface
{
    public function send(): array|null;
}