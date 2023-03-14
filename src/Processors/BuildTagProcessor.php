<?php

namespace Healthengine\LaravelLogging\Processors;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class BuildTagProcessor implements ProcessorInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(LogRecord $record)
    {
        // add the docker build tag if present
        if (config('app.build_tag')) {
            $record['extra']['build_tag'] = config('app.build_tag');
        }

        return $record;
    }
}
