# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v4.1.0] - 2024-11-29

### Added

- AWS trace ID header added to monolog record `X-Amzn-Trace-Id`

### Removed

- Removed `unique_id` log field

## [v4.0.4] - 2024-08-29

### Added

- Added support for Laravel 11.

## [v4.0.3] - 2024-07-24

### Added

- Added support for PHP 8.3.

## [v4.0.2] - 2023-08-08

### Added

- Added new logging context `extra.forwarded_for` which uses the value of `$_SERVER['HTTP_X_FORWARDED_FOR']`.

### Changed

- Changed logging context `extra.ip` to use value of `$_SERVER['REMOTE_ADDR']` instead of
  `$_SERVER['HTTP_X_FORWARDED_FOR']`.

## [v4.0.1] - 2023-08-03

### Added

- Added new logging context `extra.user_agent` which uses the value of `$_SERVER['HTTP_USER_AGENT']`.

## [v4.0.0] - 2023-03-14

### Added

- Added support for Laravel 10.
- Added support for Monolog 3.
- Added support for PHP 8.2.

### Removed

- Removed support for Laravel 7, 8, and 9.
- Removed support for Monolog 2.
- Removed support for PHP 7.4, and 8.0.

## [v3.0.4] - 2023-08-08

### Added

- Added new logging context `extra.forwarded_for` which uses the value of `$_SERVER['HTTP_X_FORWARDED_FOR']`.

### Changed

- Changed logging context `extra.ip` to use value of `$_SERVER['REMOTE_ADDR']` instead of
  `$_SERVER['HTTP_X_FORWARDED_FOR']`.

## [v3.0.3] - 2023-08-03

### Added

- Added new logging context `extra.user_agent` which uses the value of `$_SERVER['HTTP_USER_AGENT']`.

## [v3.0.2]

### Added

- Add Circle CI.

### Removed

- Removed support for Laravel 10. Major version of `monolog/monolog` changes with this version of Laravel which is not
  compatible with this version of the library.

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

[v4.0.4]: https://github.com/HealthengineAU/laravel-logging/compare/v4.0.3...v4.0.4
[v4.0.3]: https://github.com/HealthengineAU/laravel-logging/compare/v4.0.2...v4.0.3
[v4.0.2]: https://github.com/HealthengineAU/laravel-logging/compare/v4.0.1...v4.0.2
[v4.0.1]: https://github.com/HealthengineAU/laravel-logging/compare/v4.0.0...v4.0.1
[v4.0.0]: https://github.com/HealthengineAU/laravel-logging/compare/v3.0.4...v4.0.0
[v3.0.4]: https://github.com/HealthengineAU/laravel-logging/compare/v3.0.3...v3.0.4
[v3.0.3]: https://github.com/HealthengineAU/laravel-logging/compare/v3.0.2...v3.0.3
[v3.0.2]: https://github.com/HealthengineAU/laravel-logging/compare/v3.0.1...v3.0.2
[v3.0.1]: https://github.com/HealthengineAU/laravel-logging/compare/v3.0.0...v3.0.1
[v3.0.0]: https://github.com/HealthengineAU/laravel-logging/compare/v2.0.4....v3.0.0
