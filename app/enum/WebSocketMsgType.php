<?php
/**
 * Author 王小大 [m@wangxiaoda.com]
 * DateTime 2022/8/14 13:43
 * Description 描述信息
 **/

namespace App\enum;

class WebSocketMsgType
{
    const AUTH      = "auth"; // 权限
    const INIT      = "init"; // 初始化消息
    const TEXT      = "text"; // 文本消息
    const ROOM_INFO = "roomInfo"; // 房间信息
    const ERROR     = "error"; // 文本消息
    const PING      = "ping"; // PING
}