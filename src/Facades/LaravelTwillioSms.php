<?php

declare(strict_types=1);

namespace CreativeCrafts\LaravelTwillioSms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \creativeCrafts\LaravelTwillioSms\LaravelTwillioSms
 */
class LaravelTwillioSms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \CreativeCrafts\LaravelTwillioSms\LaravelTwillioSms::class;
    }
}
