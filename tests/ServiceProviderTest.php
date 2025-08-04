<?php

namespace Healthengine\LaravelLogging\Tests;

use Healthengine\LaravelLogging\ServiceProvider;
use Illuminate\Support\Facades\Context;
use Orchestra\Testbench\TestCase;

/**
 * @covers \Healthengine\LaravelLogging\ServiceProvider
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

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }
}
