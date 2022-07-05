<?php

use Healthengine\LaravelLogging\Taps\LogstashTap;
use Healthengine\LaravelLogging\Taps\ProcessorTap;
use Monolog\Handler\StreamHandler;

$taps = [
    LogstashTap::class,
    ProcessorTap::class,
];

return [
    'logstash' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'tap' => $taps,
    ],

    'stderr' => [
        'driver' => 'monolog',
        'handler' => StreamHandler::class,
        'level' => env('LOG_LEVEL', 'debug'),
        'tap' => $taps,
        'with' => [
            'stream' => 'php://stderr',
        ],
    ],

    'stdout' => [
        'driver' => 'monolog',
        'handler' => StreamHandler::class,
        'level' => env('LOG_LEVEL', 'debug'),
        'tap' => $taps,
        'with' => [
            'stream' => 'php://stdout',
        ],
    ],
];
