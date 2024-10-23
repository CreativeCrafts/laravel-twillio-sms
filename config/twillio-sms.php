<?php

declare(strict_types=1);

return [
    // Twilio Account SID
    'account_sid' => env('TWILIO_ACCOUNT_SID'),
    // Twilio Auth Token
    'auth_token' => env('TWILIO_AUTH_TOKEN'),
    // Twilio SMS From
    'sms_from' => env('TWILIO_SMS_FROM'),
    // The Lookup v2 API allows you to query information on a phone number so that you can make a trusted interaction with your user.
    // With this, you can format and validate phone numbers with the free Basic Lookup request
    // and add on data packages to get even more in-depth carrier and caller information.
    // currently supported fields: 'line_type_intelligence', 'sms_pumping_risk' for more information
    'phone_number_lookup' => [
        'fields' => [
            'line_type_intelligence',
            'sms_pumping_risk',
        ],
        'sms_pumping_risk' => [
            // low risk: 0 - 59
            // mild risk: 60 - 74
            // moderate risk: 75 - 89
            // high risk: 90 - 100
            // visit https://www.twilio.com/docs/lookup/v2-api/sms-pumping-risk for more information
            'max_allowed_sms_pumping_risk_score' => 59,
        ],
    ],
];
