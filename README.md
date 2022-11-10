# Laravel Logging

[![Latest Stable Version](https://poser.pugx.org/healthengine/laravel-logging/version)](https://packagist.org/packages/healthengine/laravel-logging)
[![Total Downloads](https://poser.pugx.org/healthengine/laravel-logging/downloads)](https://packagist.org/packages/healthengine/laravel-logging)
[![Build Status](https://travis-ci.com/HealthEngineAU/laravel-logging.svg?branch=master)](https://travis-ci.com/HealthEngineAU/laravel-logging)

This is a custom package designed for Laravel. It provides a logging stack pre-configured to format for Logstash and
adds multiple useful Monolog processors.

It includes the following processors to enrich logs with extra data:

- [MemoryPeakUsageProcessor](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Processor/MemoryPeakUsageProcessor.php)
  which adds the peak memory usage using the `memory_get_peak_usage()` PHP function,
- [MemoryUsageProcessor](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Processor/MemoryUsageProcessor.php)
  which adds the current memory usage using the `memory_get_usage()` PHP function,
- [UidProcessor](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Processor/UidProcessor.php) which adds a
  unique ID to each instance of the logger class - useful to track all logs across a
  single request,
- [WebProcessor](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Processor/WebProcessor.php) which adds the
  current request URI, request method and client IP to a log record,
- [BuildTagProcessor](https://github.com/HealthEngineAU/laravel-logging/blob/master/src/Processors/BuildTagProcessor.php)
  which is designed for use in docker and will add the image tag to the logs.
- [IntrospectionProcessor](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Processor/IntrospectionProcessor.php) which adds the
  file, class, function and line where the log was triggered.

## Usage

A `logstash_single`, `logstash_stderr`, and `logstash_stdout` logger have been made available for use as logging channels. You can use these in a stack
along with some others or use them standalone. Simply set your `LOG_CHANNEL` environment variable to either of those
pre-configured channels.

To customise configuration of these channels,
[copy the contents of `laravel-logging.php`](./config/laravel-logging.php) to the `channels` key in the application's
`config/logging.php` file.

The `logstash_stderr` channel includes a formatter for Logstash, the only difference compared to the `logstash` channel is that
the log lines go to STDERR instead of to a file. This is useful for a Laravel application running in docker.

The `logstash_stdout` channel includes a formatter for Logstash, the only difference compared to the `logstash` channel is that
the log lines go to STDOUT instead of to a file. This is useful for a Laravel application running in docker.

_Note:_ The `logstash_stdout` channel cannot be used with PHP FPM processes because PHP FPM makes STDOUT unavailable internally.

_Note:_ The `BuildTagProcessor` requires you to inject the Docker build tag into `build_tag` key in `config/app.php`. If
it is not present, the value is not added to the logs.

## License

Laravel Logging is licensed under the MIT license.
