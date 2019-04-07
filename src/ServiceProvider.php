<?php

namespace HealthEngine\LaravelLogging;

use HealthEngine\LaravelLogging\Taps\LogstashTap;
use HealthEngine\LaravelLogging\Taps\ProcessorTap;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Monolog\Handler\StreamHandler;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // reset the cached log instance for all queue workers. Because the queue worker is a long running process, the
        // monolog uid processor is not useful because every job uses the same uid from the same cached instance of the
        // logger. This instead removes the caching between jobs so each job will then have a unique uid which allows
        // easier debugging and auditing.
        Queue::looping(function () {
            app('log')->reset();
        });

        // add a preconfigured log channel for logstash
        config(['logging.channels.logstash' => [
            'driver' => 'single',
            'path' => env('LOG_STREAM', storage_path('logs/app.log')),
            'level' => env('LOG_LEVEL', 'info'),
            'tap' => [
                LogstashTap::class,
                ProcessorTap::class,
            ],
        ]]);

        // add a preconfigured log channel for stdout
        config(['logging.channels.stdout' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stdout',
            ],
            'tap' => [
                LogstashTap::class,
                ProcessorTap::class,
            ],
        ]]);
    }
}
