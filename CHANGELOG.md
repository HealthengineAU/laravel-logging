# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [v3.0.1] - 2023-02-16

### Added

- Added support for Laravel 10.

## [v3.0.0] - 2022-07-05

### Added

- Added `logstash_stderr` channel definition. This differs from the Laravel version of it by adding this library's taps to it.
- Started testing this library against PHP 8.0 in Travis CI.

### Changed

- Log channels `logstash` and `stdout` are no longer magically defined in the service provider. They need to be 
  explicitly defined in the application's `logging.php` configuration file now.
- Log channel `logstash` uses a default path of `storage_path('logs/laravel.log')`.
- Root namespace has been changed to `Healthengine`.
- Supported versions of `illuminate/log` has been changed to `^7.0 || ^8.0 || ^9.0`.
- Supported versions of `illuminate/queue` has been changed to `^7.0 || ^8.0 || ^9.0`.
- Supported versions of `illuminate/support` has been changed to `^7.0 || ^8.0 || ^9.0`.
- Log channel `stdout` logging level can be configured with `LOG_LEVEL` environment variable.
- Renamed log channel `stdout` to `logstash_stdout`.
- Renamed log channel `logstash` to `logstash_single`.

### Removed

- The `LOG_STREAM` environment variable has been removed.
- Removed support for versions `~5.7` and `^6.0` of `illuminate/log`.
- Removed support for versions `~5.7` and `^6.0` of `illuminate/queue`.
- Removed support for versions `~5.7` and `^6.0` of `illuminate/support`.
- Removed testing against PHP 7.3 in Travis CI.
- Removed support for versions `^7.5` and `^8.0` of `phpunit/phpunit`.
