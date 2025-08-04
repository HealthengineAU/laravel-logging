<?php

namespace Healthengine\LaravelLogging;

use Healthengine\LaravelLogging\Middleware\AddAmznTraceIdToContext;
use Illuminate\Foundation\Http\Kernel;
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

        if (config('laravel-logging.enable_tracing_middleware')) {
            $this->app->afterResolving(Kernel::class, function (Kernel $kernel) {
                $kernel->pushMiddleware(AddAmznTraceIdToContext::class);
            });
        }
    }

    public function register()
    {
        $this->publishes([__DIR__ . '/../config/laravel-logging.php' => config_path('laravel-logging.php')]);
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-logging-channels.php', 'logging.channels');
    }
}
