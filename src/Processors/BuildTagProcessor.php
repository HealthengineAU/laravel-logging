<?php

namespace Healthengine\LaravelLogging\Processors;

use Monolog\Processor\ProcessorInterface;

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
