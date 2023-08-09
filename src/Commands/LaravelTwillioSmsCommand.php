<?php

namespace creativeCrafts\LaravelTwillioSms\Commands;

use Illuminate\Console\Command;

class LaravelTwillioSmsCommand extends Command
{
    public $signature = 'laravel-twillio-sms';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
