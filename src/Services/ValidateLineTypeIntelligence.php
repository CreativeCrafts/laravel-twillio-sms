<?php

declare(strict_types=1);

namespace CreativeCrafts\LaravelTwillioSms\Services;

use CreativeCrafts\LaravelTwillioSms\Contracts\ValidationChecksContract;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Lookups\V2\PhoneNumberInstance;

final readonly class ValidateLineTypeIntelligence implements ValidationChecksContract
{
    public function __construct(
        protected PhoneNumberInstance $phoneNumberLookUp,
    ) {
    }

    /**
     * Validates if the phone number is a mobile number.
     *
     * @throws TwilioException If the phone number is not a mobile number.
     */
    public function __invoke(): void
    {
        /** @var array $lineTypeIntelligence */
        $lineTypeIntelligence = $this->phoneNumberLookUp->lineTypeIntelligence;

        if ($lineTypeIntelligence['type'] !== 'mobile' && $lineTypeIntelligence['type'] !== 'unknown') {
            throw new TwilioException('Phone number is not a mobile number.');
        }
    }
}
