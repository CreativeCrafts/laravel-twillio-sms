# A simple package to send sms messages using twillio

[![Latest Version on Packagist](https://img.shields.io/packagist/v/creativecrafts/laravel-twillio-sms.svg?style=flat-square)](https://packagist.org/packages/creativecrafts/laravel-twillio-sms)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/creativecrafts/laravel-twillio-sms/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/creativecrafts/laravel-twillio-sms/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/creativecrafts/laravel-twillio-sms/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/creativecrafts/laravel-twillio-sms/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/creativecrafts/laravel-twillio-sms.svg?style=flat-square)](https://packagist.org/packages/creativecrafts/laravel-twillio-sms)

This is a simple package to send sms messages using twillio sms service.

## Installation

You can install the package via composer:

```bash
composer require creativecrafts/laravel-twillio-sms
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="twillio-sms-config"
```

This is the contents of the published config file:

```php
return [
    'account_sid' => env('TWILIO_ACCOUNT_SID'),
    'auth_token' => env('TWILIO_AUTH_TOKEN'),
    'sms_from' => env('TWILIO_SMS_FROM'),
];
```

## Usage

```php
$laravelTwillioSms = \creativeCrafts\LaravelTwillioSms\Facades\LaravelTwillioSms::sendSms('+1234566798', 'Hello World from Laravel Twillio SMS');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Godspower Oduose](https://github.com/rockblings)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
