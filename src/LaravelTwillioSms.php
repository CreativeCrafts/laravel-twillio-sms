<?php

namespace creativeCrafts\LaravelTwillioSms;

use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

class LaravelTwillioSms
{
    /**
     * @throws TwilioException
     */
    public static function sendSms(string $number, string $message): MessageInstance
    {
        /** @var string $account_sid */
        $account_sid = config('twillio-sms.account_sid');

        /** @var string $auth_token */
        $auth_token = config('twillio-sms.auth_token');

        $client = new Client($account_sid, $auth_token);
        return $client->messages->create($number, [
            'from' => config('twillio-sms.from'),
            'body' => $message,
        ]);
    }
}
