<?php

namespace creativeCrafts\LaravelTwillioSms;

use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

class LaravelTwillioSms
{
    protected Client $client;

    /**
     * @throws ConfigurationException
     */
    public function __construct()
    {
        /** @var string $account_sid */
        $account_sid = config('twillio-sms.account_sid');

        /** @var string $auth_token */
        $auth_token = config('twillio-sms.auth_token');

        $this->client = new Client($account_sid, $auth_token);
    }

    /**
     * @throws TwilioException
     */
    public function sendSms(string $number, string $message): MessageInstance
    {
        return $this->client->messages->create($number, [
            'from' => config('twillio-sms.from'),
            'body' => $message,
        ]);
    }
}
