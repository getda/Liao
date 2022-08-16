<?php

use Webman\GatewayWorker\Gateway;
use Webman\GatewayWorker\BusinessWorker;
use Webman\GatewayWorker\Register;

return [
    'gateway'  => [
        'handler'     => Gateway::class,
        'listen'      => envs('WEBSOCKET_LISTEN', 'websocket://0.0.0.0:8282'),
        'count'       => cpu_count(),
        'constructor' => [
            'config' => [
                'lanIp'           => '127.0.0.1',
                'startPort'       => 2300,
                'pingInterval'    => 25,
                'pingData'        => '{"type":"ping"}',
                'registerAddress' => '127.0.0.1:1236',
                'onConnect'       => function () {
                },
            ],
        ],
    ],
    'worker'   => [
        'handler'     => BusinessWorker::class,
        'count'       => cpu_count() * 2,
        'constructor' => [
            'config' => [
                'eventHandler'    => plugin\webman\gateway\Events::class,
                'name'            => 'ChatBusinessWorker',
                'registerAddress' => '127.0.0.1:1236',
            ],
        ],
    ],
    'register' => [
        'handler'     => Register::class,
        'listen'      => 'text://0.0.0.0:1236',
        'count'       => 1, // Must be 1
        'constructor' => [],
    ],
];
