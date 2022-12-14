<?php
/*
|--------------------------------------------------------------------------
| Base
|--------------------------------------------------------------------------
*/
$app = [
    'DEBUG'                  => false,
    // WEB 服务
    'SERVER_LISTEN'          => "http://0.0.0.0:8787",
    // WEBSOCKET 服务
    'WEBSOCKET_LISTEN'       => "websocket://0.0.0.0:8282",
    // WEBSOCKET 连接地址，用于前台
    'FRONTEND_WEBSOCKET_URL' => "ws://127.0.0.1:8282",
];

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/
$database = [
    'DB_CONNECTION' => 'mysql',
    'DB_HOST'       => '127.0.0.1',
    'DB_PORT'       => '3306',
    'DB_DATABASE'   => 'test',
    'DB_USERNAME'   => 'root',
    'DB_PASSWORD'   => '',
];

/*
|--------------------------------------------------------------------------
| Redis
|--------------------------------------------------------------------------
*/
$redis = [
    'REDIS_HOST'     => '127.0.0.1',
    'REDIS_PORT'     => 6379,
    'REDIS_DATABASE' => 0,
    'REDIS_PASSWORD' => null,
];

return array_merge($app, $database, $redis);