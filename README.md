# A simple package to send sms messages using twillio

[![Latest Version on Packagist](https://img.shields.io/packagist/v/creativecrafts/laravel-twillio-sms.svg?style=flat-square)](https://packagist.org/packages/creativecrafts/laravel-twillio-sms)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/creativecrafts/laravel-twillio-sms/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/creativecrafts/laravel-twillio-sms/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/creativecrafts/laravel-twillio-sms/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/creativecrafts/laravel-twillio-sms/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/creativecrafts/laravel-twillio-sms.svg?style=flat-square)](https://packagist.org/packages/creativecrafts/laravel-twillio-sms)

# Laravel Twilio SMS

Laravel Twilio SMS is a simple, elegant package for sending SMS messages using the Twilio API within your Laravel applications. This package allows you to validate phone numbers and send SMS messages effortlessly.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Method Reference](#method-reference)
- [Testing](#testing)
- [License](#license)

## Requirements

- PHP 8.2 or higher
- Laravel 10.x or higher

## Installation

You can install the package via Composer:

```bash
composer require creativecrafts/laravel-twillio-sms
```

## Configuration

After installing the package, you need to publish the configuration file:
    
```bash
php artisan vendor:publish --tag="twillio-sms-config"
```
This will create a config/twillio-sms.php file where you can set up your Twilio credentials:

```php
return [
    'account_sid' => env('TWILIO_ACCOUNT_SID'),
    'auth_token' => env('TWILIO_AUTH_TOKEN'),
    'sms_from' => env('TWILIO_SMS_FROM'),
    // The Lookup v2 API allows you to query information on a phone number so that you can make a trusted interaction with your user.
    // With this, you can format and validate phone numbers with the free Basic Lookup request
    // and add on data packages to get even more in-depth carrier and caller information.
    // currently supported fields: 'line_type_intelligence', 'sms_pumping_risk' for more information
    'phone_number_lookup' => [
        'fields' => [
            'line_type_intelligence',
            'sms_pumping_risk',
        ],
        'sms_pumping_risk' => [
            // low risk: 0 - 59
            // mild risk: 60 - 74
            // moderate risk: 75 - 89
            // high risk: 90 - 100
            // visit https://www.twilio.com/docs/lookup/v2-api/sms-pumping-risk for more information
            'max_allowed_sms_pumping_risk_score' => 59,
        ],
    ],
];
```
Ensure you have the following environment variables set in your .env file:
    
```bash
TWILIO_ACCOUNT_SID=your_twilio_account_sid
TWILIO_AUTH_TOKEN=your_twilio_auth_token
TWILIO_SMS_FROM=your_twilio_phone_number
```

## Usage

To send an SMS, you can create an instance of the LaravelTwillioSms class and chain the methods to set the recipient’s phone number, the message content, and the sender’s number.

Example
    
```php
use CreativeCrafts\LaravelTwillioSms\LaravelTwillioSms;

$sms = LaravelTwillioSms::init()
    ->setPhoneNumber('+1234567890')
    ->setMessage('Hello, this is a test message!')
    ->send();

if ($sms) {
    echo "Message sent successfully!";
} else {
    echo "Failed to send the message.";
}
```

## Method Reference
### init()
```php
public static function init(): self
```
Creates a new instance of the LaravelTwillioSms class.

### setFrom(string $from): self

Sets the ‘from’ phone number for the SMS messages.

### setPhoneNumber(string $number): self

Sets the recipient’s phone number for the SMS message.

### setMessage(string $message): self

Sets the content of the SMS message.

### send(): bool

Sends the SMS message. Returns true if the message is sent successfully, or throws a TwilioException if an error occurs.

### sendSms(string $number, string $message): bool

### - Deprecated. Use send() instead. This method sends an SMS using the provided number and message directly.

### Getters

	•	getPhoneNumber(): string
	•	getMessage(): string
	•	getSentFrom(): string

### validatePhoneNumber(): bool

Validates the recipient’s phone number using Twilio’s Lookup API.

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
