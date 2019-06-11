<?php

namespace HealthEngine\LaravelLogging\Processors;

use Monolog\Processor\ProcessorInterface;

class UrlPatternProcessor implements ProcessorInterface
{
    /**
     * @param  array $records
     * @return array
     */
    public function __invoke(array $records)
    {
        // get the current request, taking care that this might be in a CLI context
        $uri = optional(request()->route())->uri();

        // add the patterned url to the log context
        if (filled($uri)) {
            $records['extra']['url_pattern'] = optional(app('router')->getCurrentRoute())->uri();
        }

        return $records;
    }
}
