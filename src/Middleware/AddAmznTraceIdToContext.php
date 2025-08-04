<?php

declare(strict_types=1);

namespace Healthengine\LaravelLogging\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;

class AddAmznTraceIdToContext
{
    /**
     * @param Request $request
     * @param Closure(Request): Response $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $traceIdHeaderValue = $request->header('X-Amzn-Trace-Id');

        if (is_string($traceIdHeaderValue)) {
            Context::addIf('X-Amzn-Trace-Id', $traceIdHeaderValue);
        }

        return $next($request);
    }
}
