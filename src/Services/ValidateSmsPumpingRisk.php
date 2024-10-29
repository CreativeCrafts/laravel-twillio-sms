<?php

declare(strict_types=1);

namespace CreativeCrafts\LaravelTwillioSms\Services;

use CreativeCrafts\LaravelTwillioSms\Contracts\ValidationChecksContract;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Lookups\V2\PhoneNumberInstance;

/**
 * Validates the SMS pumping risk of the recipient's phone number.
 */
final readonly class ValidateSmsPumpingRisk implements ValidationChecksContract
{
    public function __construct(
        protected PhoneNumberInstance $phoneNumberLookUp
    ) {
    }

    /**
     * Validates the SMS pumping risk for a given phone number.
     *
     * @throws TwilioException If the phone number has a high SMS pumping risk score, an error code, or is blocked.
     */
    public function __invoke(): bool
    {
        /** @var int $maxAllowedSmsPumpingRiskScore */
        $maxAllowedSmsPumpingRiskScore = config('twillio-sms.phone_number_lookup.sms_pumping_risk.max_allowed_sms_pumping_risk_score');

        /** @var array $smsPumpingRisk */
        $smsPumpingRisk = $this->phoneNumberLookUp->smsPumpingRisk;

        if ($smsPumpingRisk['sms_pumping_risk_score'] > $maxAllowedSmsPumpingRiskScore) {
            throw new TwilioException('Phone number has a high SMS pumping risk score.');
        }

        if ($smsPumpingRisk['error_code'] !== null) {
            throw new TwilioException('Phone number has an error code:' . $smsPumpingRisk['error_code']);
        }

        if ($smsPumpingRisk['number_blocked']) {
            throw new TwilioException('Phone number is blocked.');
        }
        return true;
    }
}
