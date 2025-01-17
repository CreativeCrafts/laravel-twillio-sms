# Changelog

All notable changes to `laravel-twillio-sms` will be documented in this file.

## 0.0.3 - 2023-08-22

- Updated the LaravelTwillioSms class and make it static

## 0.0.2 - 2023-08-22

- Updated README.md

## 0.0.1 - 2023-08-22

- Initial release

## 0.0.2 - 2023-08-22

- Updated README.md

## 0.0.3 - 2023-08-22

- Updated the LaravelTwillioSms class and make it static

## 0.0.4 - 2023-08-22

- Fixed a bug in the LaravelTwillioSms class that was not including the sender's phone number

## 0.0.5 - 2023-08-22

- Updated composer packages

## 0.0.6 - 2023-08-24

- Added a contract to the LaravelTwillioSms class and updated test

## 0.0.7 - 2024-03-13

- Added support for laravel 11
- Remove support for php 8.1

## 1.0.0 - 2024-03-17

- Bump version to 1.0.0

## 1.1.0 - 2024-10-29
    Features:
    - Added phone number lookup support
    - Added support for Twilio SMS pumping risk
    - Added support for Twilio line type intelligence
    - Added new configuration file `config/twillio-sms.php`
    - Added new methods `setPhoneNumber`, `setMessage`, and `send`
    - Refactored and added new classes and methods
    - Introduced new tests and test setups

    1. Workflow changes:
        - Updated GitHub Actions workflow `fix-php-code-style-issues.yml` to use `aglipanci/laravel-pint-action@2.4`.
        - Added a new GitHub Actions workflow `rector.yaml` for running Rector.
        - Updated the `run-tests.yml` workflow to include Pest plugins.

    2. `composer.json` updates:
        - Added `larastan/larastan`, `mockery/mockery`, `rector/rector`, `symplify/easy-coding-standard`, and `rector/swiss-knife` to `require-dev`.
        - Updated versions for `pestphp/pest`, `pestphp/pest-plugin-arch`, and `pestphp/pest-plugin-laravel`.
        - Changes in autoloading for PSR-4 and autoload-dev namespaces.

    3. Configuration and new files:
       - Updated `config/twillio-sms.php` to add support for phone number lookup.
       - Added new configuration files: `ecs.php`, `phpstan.neon.dist`, `pint.json`, and `rector.php`.

    4. Codebase updates:
       - Refactored and added several new methods and classes such as `ValidationChecks`, `ValidateLineTypeIntelligence`, and `ValidateSmsPumpingRisk`.
       - Deprecated method `sendSms` and introduced a more streamlined approach with separate `setPhoneNumber`, `setMessage`, and `send` methods.

    5. Namespace and file permission updates:
       - Changes in namespaces from `creativeCrafts` to `CreativeCrafts`.

    6. Tests:
       - Extensive changes and additions in tests including introducing `pest` test setups and mocked Twilio calls.

    Overall, these changes introduce new workflows, dependencies, namespace adjustments, and additional testing structures significantly enhancing the package's capabilities and maintainability.

## 1.2.0 - 2025-01-17

- Temporary fix for Twilio SMS pumping risk issue