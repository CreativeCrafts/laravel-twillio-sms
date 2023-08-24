<?php

namespace creativeCrafts\LaravelTwillioSms\Contracts;

interface LaravelTwillioSmsContract
{
    public function sendSms(string $number, string $message): bool;
}
