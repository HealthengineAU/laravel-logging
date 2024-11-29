<?php

namespace Healthengine\LaravelLogging\Taps;

use Healthengine\LaravelLogging\Formatters\LogstashFormatter;

class LogstashTap
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        $formatter = new LogstashFormatter(config('app.name'));

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
        }
    }
}
