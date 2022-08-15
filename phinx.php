<?php

return [
    'paths'         => [
        "migrations" => "database/migrations",
        "seeds"      => "database/seeds",
    ],
    'environments'  => [
        'default_migration_table' => 'phinxlog',
        'default_environment'     => 'development',
        'production'              => [
            'adapter' => 'mysql',
            'host'    => 'localhost',
            'name'    => 'liao_test',
            'user'    => 'liao_test',
            'pass'    => '2HWNKwXiHdT5eGw3',
            'port'    => '3306',
            'charset' => 'utf8mb4',
        ],
        // 'development'             => [ // 家
        //     "adapter" => 'mysql',
        //     "host"    => '127.0.0.1',
        //     "name"    => 'liao',
        //     "user"    => 'root',
        //     "pass"    => 'root',
        //     "port"    => '3306',
        //     "charset" => "utf8mb4",
        // ],
        'development'             => [
            "adapter" => 'mysql',
            "host"    => '127.0.0.1',
            "name"    => 'liao',
            "user"    => 'xiaoda',
            "pass"    => '123',
            "port"    => '3306',
            "charset" => "utf8mb4",
        ],
        'testing'                 => [
            'adapter' => 'mysql',
            'host'    => 'localhost',
            'name'    => 'testing_db',
            'user'    => 'root',
            'pass'    => '',
            'port'    => '3306',
            'charset' => 'utf8mb4',
        ],
    ],
    'version_order' => 'creation',
];
