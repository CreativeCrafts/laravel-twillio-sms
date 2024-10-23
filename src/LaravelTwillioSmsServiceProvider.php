<?php

declare(strict_types=1);

namespace CreativeCrafts\LaravelTwillioSms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelTwillioSmsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-twillio-sms')
            ->hasConfigFile();
    }
}
