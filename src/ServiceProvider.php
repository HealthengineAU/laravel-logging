<?php

namespace Healthengine\LaravelLogging;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

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
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-logging.php', 'logging.channels');
    }
}
