<?php

declare(strict_types=1);

namespace CreativeCrafts\LaravelTwillioSms\Contracts;

interface LaravelTwillioSmsContract
{
    public static function init(): self;

    public function setPhoneNumber(string $number): self;

    public function setMessage(string $message): self;

    public function send(): bool;
}
