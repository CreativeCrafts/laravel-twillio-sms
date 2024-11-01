<?php

declare(strict_types=1);

namespace CreativeCrafts\LaravelTwillioSms\Tests;

use CreativeCrafts\LaravelTwillioSms\LaravelTwillioSmsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'CreativeCrafts\\LaravelTwillioSms\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-twillio-sms_table.php.stub';
        $migration->up();
        */
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelTwillioSmsServiceProvider::class,
        ];
    }
}
