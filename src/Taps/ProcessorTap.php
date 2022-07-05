<?php

namespace Healthengine\LaravelLogging\Taps;

use Healthengine\LaravelLogging\Processors\BuildTagProcessor;
use Healthengine\LaravelLogging\Processors\UrlPatternProcessor;
use Monolog\Logger as MonoLogger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;

class ProcessorTap
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        $logger
            ->pushProcessor(new BuildTagProcessor())
            ->pushProcessor(new MemoryPeakUsageProcessor(true, false))
            ->pushProcessor(new MemoryUsageProcessor(true, false))
            ->pushProcessor(new UidProcessor())
            ->pushProcessor(new UrlPatternProcessor())
            ->pushProcessor(new IntrospectionProcessor(MonoLogger::DEBUG, ['Illuminate\\']))
            ->pushProcessor(
                new WebProcessor(
                    null,
                    [
                        'http_method' => 'REQUEST_METHOD',
                        'ip' => 'HTTP_X_FORWARDED_FOR',
                        'referrer' => 'HTTP_REFERER',
                        'server' => 'SERVER_NAME',
                        'unique_id' => 'HTTP_X_AMZN_TRACE_ID',
                        'url' => 'REQUEST_URI',
                    ])
            );
    }
}
