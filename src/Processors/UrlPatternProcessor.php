<?php

namespace Healthengine\LaravelLogging\Processors;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class UrlPatternProcessor implements ProcessorInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(LogRecord $record)
    {
        // get the current request, taking care that this might be in a CLI context
        $uri = optional(request()->route())->uri();

        // add the patterned url to the log context
        if (filled($uri)) {
            $record['extra']['url_pattern'] = $uri;
        }

        return $record;
    }
}
