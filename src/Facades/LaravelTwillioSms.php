<?php

namespace creativeCrafts\LaravelTwillioSms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \creativeCrafts\LaravelTwillioSms\LaravelTwillioSms
 */
class LaravelTwillioSms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \creativeCrafts\LaravelTwillioSms\LaravelTwillioSms::class;
    }
}
