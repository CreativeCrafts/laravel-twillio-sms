<?php

declare(strict_types=1);

namespace CreativeCrafts\LaravelTwillioSms\Services;

use CreativeCrafts\LaravelTwillioSms\Contracts\ValidationChecksContract;
use Illuminate\Support\Facades\Config;
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
     * Performs validation checks on the phone number based on configured fields.
     *
     * This method retrieves validation checks from the configuration and executes
     * the appropriate validation for each specified field. It also checks if risk
     * assessment is enabled before performing SMS pumping risk validation.
     *
     * @return bool Returns true if all validation checks pass successfully.
     *              If any check fails, a TwilioException is thrown.
     *
     * @throws TwilioException If any validation check fails.
     */
    public function __invoke(): bool
    {
        $validationChecks = Config::array('twillio-sms.phone_number_lookup.fields');
        $isRiskCheckEnabled = Config::boolean('twillio-sms.risk_check', true);
        foreach ($validationChecks as $field) {
            switch ($field) {
                case 'line_type_intelligence':
                    (new ValidateLineTypeIntelligence($this->phoneNumberLookUp))();
                    break;
                case 'sms_pumping_risk':
                    if ($isRiskCheckEnabled) {
                        (new ValidateSmsPumpingRisk($this->phoneNumberLookUp))();
                    }
                    break;
            }
        }
        return true;
    }
}
