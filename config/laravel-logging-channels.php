<?php

use Healthengine\LaravelLogging\Taps\LogstashTap;
use Healthengine\LaravelLogging\Taps\ProcessorTap;
use Monolog\Handler\StreamHandler;

return [
    'logstash_single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'tap' => [
            LogstashTap::class,
            ProcessorTap::class,
        ],
    ],

    'logstash_stderr' => [
        'driver' => 'monolog',
        'handler' => StreamHandler::class,
        'level' => env('LOG_LEVEL', 'debug'),
        'tap' => [
            LogstashTap::class,
            ProcessorTap::class,
        ],
        'with' => [
            'stream' => 'php://stderr',
        ],
    ],

    'logstash_stdout' => [
        'driver' => 'monolog',
        'handler' => StreamHandler::class,
        'level' => env('LOG_LEVEL', 'debug'),
        'tap' => [
            LogstashTap::class,
            ProcessorTap::class,
        ],
        'with' => [
            'stream' => 'php://stdout',
        ],
    ],
];
