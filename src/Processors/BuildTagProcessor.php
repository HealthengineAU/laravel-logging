<?php

namespace Healthengine\LaravelLogging\Processors;

use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

if (Logger::API === 3) {
    class BuildTagProcessor implements ProcessorInterface
    {
        public function __invoke(LogRecord $record): LogRecord
        {
            // add the docker build tag if present
            if (config('app.build_tag')) {
                $record['extra']['build_tag'] = config('app.build_tag');
            }

            return $record;
        }
    }
} else {
    class BuildTagProcessor implements ProcessorInterface
    {
        /**
         * @param  array $records
         * @return array
         */
        public function __invoke(array $records)
        {
            // add the docker build tag if present
            if (config('app.build_tag')) {
                $records['extra']['build_tag'] = config('app.build_tag');
            }

            return $records;
        }
    }
}
