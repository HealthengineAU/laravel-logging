<?php

namespace Healthengine\LaravelLogging\Tests\Middleware;

use Healthengine\LaravelLogging\Middleware\AddAmznTraceIdToContext;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Context;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(\Healthengine\LaravelLogging\Middleware\AddAmznTraceIdToContext::class)]
class AddAmznTraceIdToContextTest extends TestCase
{
    public function testNoContextAddedWithoutHeader()
    {
        $middleware = new AddAmznTraceIdToContext();
        $request = new Request();
        $response = new Response('', 200);

        // call the middleware
        $middleware->handle($request, function () use ($response) {
            return $response;
        });

        $this->assertFalse(Context::has('X-Amzn-Trace-Id'));
    }

    public function testContextAddedWhenHeaderPresent()
    {
        $middleware = new AddAmznTraceIdToContext();
        $request = Request::create('/', 'GET', [], [], [], ['HTTP_X_AMZN_TRACE_ID' => 'abcd-1234']);
        $response = new Response('', 200);

        // call the middleware
        $middleware->handle($request, function () use ($response) {
            return $response;
        });

        $this->assertEquals('abcd-1234', Context::get('X-Amzn-Trace-Id'));
    }
}
