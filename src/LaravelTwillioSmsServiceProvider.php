<?php

namespace creativeCrafts\LaravelTwillioSms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use creativeCrafts\LaravelTwillioSms\Commands\LaravelTwillioSmsCommand;

class LaravelTwillioSmsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-twillio-sms')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-twillio-sms_table')
            ->hasCommand(LaravelTwillioSmsCommand::class);
    }
}
