<?php

declare(strict_types=1);

namespace CreativeCrafts\LaravelTwillioSms\Contracts;

use Twilio\Rest\Lookups\V2\PhoneNumberInstance;

interface ValidationChecksContract
{
    public function __construct(
        PhoneNumberInstance $phoneNumberLookUp
    );

    public function __invoke(): void;
}
