<?php

namespace Healthengine\LaravelLogging\Tests\Middleware;

use Healthengine\LaravelLogging\Middleware\MonitorRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Monolog\Handler\TestHandler;
use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Healthengine\LaravelLogging\Middleware\MonitorRequest
 */
class MonitorRequestTest extends TestCase
{
    public function testMiddleware()
    {
        // configure a test logger
        $handler = new TestHandler();
        $logger = new Logger('testing', [$handler]);

        $middleware = new MonitorRequest($logger);
        $request = new Request();
        $response = new Response('', 200);

        // call the middleware
        $middleware->handle($request, function () use ($response) {
            return $response;
        });

        // expect to see the correct values in the log
        $this->assertTrue($handler->hasRecord(
            [
                'message' => 'Response received',
                'context' => [
                    'http_status_code' => 200,
                ]
            ],
            Logger::API === 3 ? Level::Info : Logger::INFO
        ));
    }
}
