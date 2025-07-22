<?php

namespace Healthengine\LaravelLogging;

use Illuminate\Log\Context\Repository;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Psr\Http\Message\RequestInterface;

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

        /**
         * Add the trace ID to context if its not already present.
         *
         * Web requests will have this value set by the load balancer and so will add it to context.
         * Queue jobs will have any context persisted when they are dispatched, so for jobs that are
         * dispatched from a web request (ie not a cron) will already have this value hydrated and
         * won't try to set another value. This will also work across fan-out jobs.
         * Crons won't have this available ever.
         *
         * Any values added to context will also end up in our logs under the `extra` key. This is
         * useful for following code across services for a single web request.
         */
        Context::when(
            array_key_exists('HTTP_X_AMZN_TRACE_ID', $_SERVER),
            fn (Repository $context) => $context->addIf('X-Amzn-Trace-Id', $_SERVER['HTTP_X_AMZN_TRACE_ID']),
        );

        // For any outgoing request, carry along the AWS trace ID for better observability across services
        Http::globalRequestMiddleware(
            fn (RequestInterface $request) => $request->withHeader('X-Amzn-Trace-Id', Context::get('X-Amzn-Trace-Id'))
        );
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-logging.php', 'logging.channels');
    }
}
