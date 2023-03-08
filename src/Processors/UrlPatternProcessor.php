<?php

namespace Healthengine\LaravelLogging\Processors;

use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

if (Logger::API === 3) {
    class UrlPatternProcessor implements ProcessorInterface
    {
        public function __invoke(LogRecord $record): LogRecord
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
} else {
    class UrlPatternProcessor implements ProcessorInterface
    {
        /**
         * @param array $records
         * @return array
         */
        public function __invoke(array $records): array
        {
            // get the current request, taking care that this might be in a CLI context
            $uri = optional(request()->route())->uri();

            // add the patterned url to the log context
            if (filled($uri)) {
                $records['extra']['url_pattern'] = $uri;
            }

            return $records;
        }
    }
}
