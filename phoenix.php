<?php
include 'config/env.php';

return [
    'migration_dirs' => [
        'main' => __DIR__ . '/migrations',
    ],
    'environments' => [
        'local' => [
            'adapter' => 'mysql',
            'host' => DB_HOST,
            'port' => 3306, // optional
            'username' => DB_USERNAME,
            'password' => DB_PASSWORD,
            'db_name' => DB_NAME,
            'charset' => 'utf8'
        ],
        'default_environment' => 'local',
        'log_table_name' => 'phoenix_log',
    ]
];
