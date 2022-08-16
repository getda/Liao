<?php
$config = @include config_path()."/.env.php";

return [
    'paths'         => [
        "migrations" => "database/migrations",
        "seeds"      => "database/seeds",
    ],
    'environments'  => [
        'default_migration_table' => 'phinxlog',
        'default_environment'     => 'default',
        'default'              => [
            'adapter' => 'mysql',
            'host'    => $config['DB_HOST'] ?? '127.0.0.1',
            'name'    => $config['DB_DATABASE'] ?? 'test',
            'user'    => $config['DB_USERNAME'] ?? 'root',
            'pass'    => $config['DB_PASSWORD'] ?? 'root',
            'port'    => $config['DB_PORT'] ?? '3306',
            'charset' => 'utf8mb4',
        ]
    ],
    'version_order' => 'creation',
];
