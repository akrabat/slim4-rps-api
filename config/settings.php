<?php

declare(strict_types=1);

use Monolog\Level;

return static function (string $appEnv) {
    $settings =  [
        'app_env' => $appEnv,
        'di_compilation_path' => __DIR__ . '/../var/cache',
        'display_error_details' => false,
        'log_errors' => true,

        'logger' => [
            'name' => 'slim-app',
            'path' => 'php://stderr',
            'level' => Level::Debug,
        ],

        'db' => [
            'path' => __DIR__ . '/../db/rps.db',
            'driver' => 'pdo_sqlite',
        ]
    ];

    if ($appEnv === 'DEVELOPMENT') {
        // Overrides for development mode
        $settings['di_compilation_path'] = '';
        $settings['display_error_details'] = false;

        // To store logs to a separate file, uncomment this line
        // $settings['logger']['path'] = __DIR__ . '/../var/log/app.log';
    }

    return $settings;
};
