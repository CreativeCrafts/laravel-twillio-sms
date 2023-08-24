<?php

namespace creativeCrafts\LaravelTwillioSms;

use creativeCrafts\LaravelTwillioSms\Contracts\LaravelTwillioSmsContract;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class LaravelTwillioSms implements LaravelTwillioSmsContract
{
    /**
     * @throws TwilioException
     */
    public function sendSms(string $number, string $message): bool
    {
        /** @var string $account_sid */
        $account_sid = config('twillio-sms.account_sid');

        /** @var string $auth_token */
        $auth_token = config('twillio-sms.auth_token');

        $client = new Client($account_sid, $auth_token);

        $client->messages->create($number, [
            'from' => config('twillio-sms.sms_from'),
            'body' => $message,
        ]);

        return true;
    }
}
