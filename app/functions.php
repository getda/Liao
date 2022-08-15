<?php

use App\exception\WebSocketNoSendException;
use GatewayWorker\Lib\Gateway;

/**
 * Here is your custom functions.
 */
function send($data, $code = 200)
{
    $json = json_encode(array_merge([
        'code' => 1,
    ], $data));

    return response($json, $code, ['Content-Type' => 'application/json']);
}

function send_message($message, $code = 422, $data = [])
{
    $json = json_encode(array_merge([
        'code' => 0,
        'msg'  => $message,
    ], $data));

    return response($json, $code, ['Content-Type' => 'application/json']);
}

function cache_remember($key, $ttl, $callback)
{
    $value = \support\Cache::get($key);

    if (!is_null($value)) {
        return $value;
    }

    $value = value($callback);
    \support\Cache::set($key, $value, $ttl ?: 0);
    return $value;
}

/**
 * WebSocket 抛出异常，代表不回复任务信息
 * Author 王小大 [m@wangxiaoda.com]
 * DateTime 2022/8/13 18:18
 * @return void
 * @throws WebSocketNoSendException
 */
function websocket_no_send(): void
{
    throw new WebSocketNoSendException();
}

function websocket_send($uid, string|array|null $message, $type = "", $code = 0)
{
    if (!is_array($message)) {
        $message['msg'] = $message;
    }

    $data = array_merge([
        'code' => $code,
        'type' => $type,
    ], $message);

    Gateway::sendToUid($uid, json_encode($data, JSON_UNESCAPED_UNICODE));
}


function websocket_send_group($groupId, array $message, $type = "", $code = 0)
{
    if (!is_array($message)) {
        $message['msg'] = $message;
    }

    $data = array_merge([
        'code' => $code,
        'type' => $type,
    ], $message);

    Gateway::sendToGroup($groupId, json_encode($data, JSON_UNESCAPED_UNICODE));
}

/**
 * 十三位时间戳
 * Author 王小大 [m@wangxiaoda.com]
 * DateTime 2022/8/14 15:51
 * @return float
 */
function millisecond() {
    [$t1, $t2] = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}