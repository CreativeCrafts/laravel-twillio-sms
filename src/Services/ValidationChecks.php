<?php

declare(strict_types=1);

namespace CreativeCrafts\LaravelTwillioSms\Services;

use CreativeCrafts\LaravelTwillioSms\Contracts\ValidationChecksContract;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Lookups\V2\PhoneNumberInstance;

final readonly class ValidationChecks implements ValidationChecksContract
{
    /**
     * @param PhoneNumberInstance $phoneNumberLookUp Twilio's PhoneNumberInstance object for phone number lookup.
     */
    public function __construct(
        protected PhoneNumberInstance $phoneNumberLookUp,
    ) {
    }

    /**
     * Performs validation checks on the phone number.
     *
     * @throws TwilioException If any validation check fails.
     */
    public function __invoke(): bool
    {
        /** @var array $validationChecks */
        $validationChecks = config('twillio-sms.phone_number_lookup.fields');
        foreach ($validationChecks as $field) {
            switch ($field) {
                case 'line_type_intelligence':
                    (new ValidateLineTypeIntelligence($this->phoneNumberLookUp))();
                    break;
                case 'sms_pumping_risk':
                    (new ValidateSmsPumpingRisk($this->phoneNumberLookUp))();
                    break;
            }
        }
        return true;
    }
}
