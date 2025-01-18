<?php

declare(strict_types=1);

use CreativeCrafts\LaravelTwillioSms\Tests\TestCase;
use Illuminate\Support\Facades\Config;

uses(TestCase::class)->in(__DIR__);

pest()->project()->github('CreativeCrafts/laravel-twillio-sms');

function setDefaultConfig(bool $riskCheck = true): void
{
    Config::set('twillio-sms.risk_check', $riskCheck);
    Config::set('twillio-sms.account_sid', 'valid-account-sid');
    Config::set('twillio-sms.auth_token', 'valid-account-sid');
    Config::set('twillio-sms.sms_from', '+1234567890');
}
