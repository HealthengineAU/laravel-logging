<?php

namespace Healthengine\LaravelLogging\Tests;

use Healthengine\LaravelLogging\Processors\BuildTagProcessor;
use Healthengine\LaravelLogging\Processors\UrlPatternProcessor;
use Healthengine\LaravelLogging\ServiceProvider;
use Healthengine\LaravelLogging\Taps\LogstashTap;
use Healthengine\LaravelLogging\Taps\ProcessorTap;
use Illuminate\Queue\Events\Looping;
use Illuminate\Support\Facades\Log;
use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Orchestra\Testbench\TestCase;

/**
 * @covers \Healthengine\LaravelLogging\Processors\BuildTagProcessor
 * @covers \Healthengine\LaravelLogging\Processors\UrlPatternProcessor
 * @covers \Healthengine\LaravelLogging\Taps\LogstashTap
 * @covers \Healthengine\LaravelLogging\Taps\ProcessorTap
 * @covers \Healthengine\LaravelLogging\ServiceProvider
 */
class LoggingTest extends TestCase
{
    public function testUidResetsWhenLooping()
    {
        // configure a test logger
        $handler = new TestHandler();
        $handler->setSkipReset(true);
        $logger = new Logger('testing', [$handler]);
        $logger->pushProcessor(new UidProcessor());
        Log::swap($logger);

        // log something before the queue reset event
        Log::info('before');
        event(new Looping('connection', 'queue'));

        // and log something after
        Log::info('after');

        // then assert that the uid values are not the same
        $records = $handler->getRecords();
        $this->assertNotEquals($records[0]['extra']['uid'], $records[1]['extra']['uid']);
    }

    public function testBuildTagIncluded()
    {
        // configure a test logger
        $handler = new TestHandler();
        $logger = new Logger('testing', [$handler]);
        $logger->pushProcessor(new BuildTagProcessor());
        Log::swap($logger);
        // add the configuration property for build tag
        config(['app.build_tag' => 42]);

        // log something
        Log::info('testing');

        // then assert that the build tag is present
        $records = $handler->getRecords();
        $this->assertEquals(42, $records[0]['extra']['build_tag']);
    }

    public function testBuildTagNotIncluded()
    {
        // configure a test logger
        $handler = new TestHandler();
        $logger = new Logger('testing', [$handler]);
        $logger->pushProcessor(new BuildTagProcessor());
        Log::swap($logger);

        // log something
        Log::info('testing');

        // then assert that the build tag is not present
        $records = $handler->getRecords();
        $this->assertArrayNotHasKey('build_tag', $records[0]['extra']);
    }

    public function testUrlPatternIncluded()
    {
        // configure a test logger
        $handler = new TestHandler();
        $logger = new Logger('testing', [$handler]);
        $logger->pushProcessor(new UrlPatternProcessor());
        Log::swap($logger);

        // register a route that will log something
        $this->app->get('router')->get('api/{binding}', function ($binding) {
            Log::info('testing');
        });
        // make the request
        $this->get('api/bound');

        // then assert that the url is included
        $records = $handler->getRecords();
        $this->assertEquals('api/{binding}', $records[0]['extra']['url_pattern']);
    }

    public function testUrlPatternNotIncluded()
    {
        // configure a test logger
        $handler = new TestHandler();
        $logger = new Logger('testing', [$handler]);
        $logger->pushProcessor(new UrlPatternProcessor());
        Log::swap($logger);

        // log something
        Log::info('testing');

        // then assert that the url is not included
        $records = $handler->getRecords();
        $this->assertArrayNotHasKey('url_pattern', $records[0]['extra']);
    }

    public function testLogstashTap()
    {
        // configure a test logger
        $handler = new TestHandler();
        $logger = new Logger('testing', [$handler]);
        // run the logger through the LogstashTap
        (new LogstashTap())($logger);
        Log::swap($logger);

        // log something
        Log::info('testing');

        $records = $handler->getRecords();
        $decoded = json_decode($records[0]['formatted']);
        // assert there was no decoding error and we got an object back
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
        $this->assertTrue(is_object($decoded));
    }

    public function testProcessorTap()
    {
        // configure a test logger
        $handler = new TestHandler();
        $logger = new Logger('testing', [$handler]);
        // run the logger through the ProcessorTap
        (new ProcessorTap())($logger);
        Log::swap($logger);

        // log something
        Log::info('testing');

        // assert the log value contains the extra keys
        $record = $handler->getRecords()[0]['extra'];
        $this->assertArrayHasKey('memory_peak_usage', $record);
        $this->assertArrayHasKey('memory_usage', $record);
        $this->assertArrayHasKey('uid', $record);
    }

    public function testLogstashChannel()
    {
        $logger = Log::channel('logstash');
        $handler = $logger->getHandlers()[0];
        $formatter = $handler->getFormatter();
        $processors = $logger->getProcessors();

        // assert the logger has the logstash formatter
        $this->assertInstanceOf(LogstashFormatter::class, $formatter);
        $this->assertEquals(storage_path('logs/laravel.log'), $handler->getUrl());
        // crude assertion that the correct processors are attached
        $this->assertCount(7, $processors);
    }

    public function testStdoutChannel()
    {
        $logger = Log::channel('stdout');
        $handler = $logger->getHandlers()[0];
        $formatter = $handler->getFormatter();
        $processors = $logger->getProcessors();

        // assert the logger has the logstash formatter
        $this->assertInstanceOf(LogstashFormatter::class, $formatter);
        $this->assertEquals('php://stdout', $handler->getUrl());
        // crude assertion that the correct processors are attached
        $this->assertCount(7, $processors);
    }

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }
}
