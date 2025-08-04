<?php

namespace Healthengine\LaravelLogging\Tests;

use Healthengine\LaravelLogging\ServiceProvider;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

/**
 * @covers \Healthengine\LaravelLogging\ServiceProvider
 * @covers \Healthengine\LaravelLogging\Middleware\AddAmznTraceIdToContext
 */
class ServiceProviderTest extends TestCase
{
    public function testContextMiddlewareGloballyApplied()
    {
        $this->get('/middleware', ['X-Amzn-Trace-Id' => 'xyz-123']);

        $this->assertEquals('xyz-123', Context::get('X-Amzn-Trace-Id'));
    }

    protected function defineRoutes($router)
    {
        $router->get('/middleware', function () {
            return response('Middleware applied');
        });
    }

    public function testHttpRequestMiddleware()
    {
        Context::add('X-Amzn-Trace-Id', 'abcd-1234');
        $header = null;

        Http::stub(function (Request $request) use (&$header) {
            $header = $request->header('X-Amzn-Trace-Id');
            return Http::response();
        })->get('http://localhost:8000');

        $this->assertEquals(['abcd-1234'], $header);
    }

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }
}
