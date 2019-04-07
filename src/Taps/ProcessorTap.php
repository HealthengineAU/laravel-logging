<?php

namespace HealthEngine\LaravelLogging\Taps;

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
            ->pushProcessor(
                new WebProcessor(null, ['ip' => 'HTTP_X_FORWARDED_FOR', 'unique_id' => 'HTTP_X_AMZN_TRACE_ID'])
            );
    }
}
