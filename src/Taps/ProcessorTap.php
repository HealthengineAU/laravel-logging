<?php

namespace Healthengine\LaravelLogging\Taps;

use Healthengine\LaravelLogging\Processors\BuildTagProcessor;
use Healthengine\LaravelLogging\Processors\UrlPatternProcessor;
use Monolog\Level;
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
            ->pushProcessor(new IntrospectionProcessor(Level::Debug, ['Illuminate\\']))
            ->pushProcessor(
                new WebProcessor(
                    null,
                    [
                        'forwarded_for' => 'HTTP_X_FORWARDED_FOR',
                        'http_method' => 'REQUEST_METHOD',
                        'ip' => 'REMOTE_ADDR',
                        'referrer' => 'HTTP_REFERER',
                        'server' => 'SERVER_NAME',
                        'url' => 'REQUEST_URI',
                        'user_agent'  => 'HTTP_USER_AGENT',
                    ],
                ),
            );
    }
}
