<?php

declare(strict_types=1);

namespace CreativeCrafts\LaravelTwillioSms;

use CreativeCrafts\LaravelTwillioSms\Contracts\LaravelTwillioSmsContract;
use CreativeCrafts\LaravelTwillioSms\Services\ValidationChecks;
use Illuminate\Support\Facades\Config;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class LaravelTwillioSms implements LaravelTwillioSmsContract
{
    protected Client $client;

    // @pest-mutate-ignore
    protected string $number = '';

    // @pest-mutate-ignore
    protected string $message = '';

    // @pest-mutate-ignore
    protected string $from = '';

    protected array $validationChecks = [];

    /**
     * Initializes the Twilio client, sets validation checks, and sets the default 'from' number.
     *
     * @throws ConfigurationException
     * @throws TwilioException
     */
    public function __construct()
    {
        $this->client = $this->setClient();

        $defaultFrom = Config::string('twillio-sms.sms_from');
        $this->from = $defaultFrom;

        $validationChecks = Config::array('twillio-sms.phone_number_lookup.fields');
        $this->validationChecks = $validationChecks;
    }

    /**
     * This method is used to create a new instance of the LaravelTwillioSms class.
     * It returns the newly created instance, allowing for method chaining.
     *
     * @return self A new instance of LaravelTwillioSms.
     */
    public static function init(): self
    {
        return new self();
    }

    /**
     * Sets the 'from' number for the SMS messages.
     *
     * This method allows you to set the 'from' number for the SMS messages that will be sent.
     * The 'from' number is the phone number that will appear on the recipient's device as the sender.
     *
     * @param string $from The 'from' phone number. It should be a valid Twilio phone number.
     *
     * @return self The instance of the class for method chaining.
     */
    public function setFrom(string $from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Sets the phone number for the SMS message.
     *
     * This method allows you to set the recipient's phone number for the SMS message.
     * It also returns the instance of the class for method chaining.
     *
     * @param string $number The recipient's phone number.
     *
     * @return self The instance of the class.
     */
    public function setPhoneNumber(string $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Sets the message content for the SMS.
     *
     * This method allows you to set the message content for the SMS that will be sent.
     * It also returns the instance of the class for method chaining.
     *
     * @param string $message The content of the SMS message.
     *
     * @return self The instance of the class.
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Sends an SMS message using Twilio.
     *
     * This method sends an SMS message to the specified recipient's phone number using the Twilio API.
     * It first validates the recipient's phone number using Twilio's Lookup API.
     * If the phone number is valid, it sends the SMS message using the Twilio client.
     *
     * @return bool True if the SMS message is sent successfully, false otherwise.
     *
     * @throws TwilioException If there is an error sending the SMS message or validating the phone number.
     * @throws ConfigurationException If there is an error with the Twilio configuration.
     */
    public function send(): bool
    {
        try {
            $this->validatePhoneNumber();
            $this->client->messages->create(
                $this->getPhoneNumber(),
                $this->smsData()
            );

            return true;
        } catch (ConfigurationException $e) {
            throw new TwilioException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws TwilioException
     *
     * @deprecated This method will be removed in version 2.0. Use `send` instead.
     */
    // @pest-mutate-ignore
    public function sendSms(string $number, string $message): bool
    {
        // @pest-mutate-ignore
        trigger_error('The sendSms is deprecated and will be removed in version 2.0. Use send instead.', E_USER_DEPRECATED);

        $account_sid = Config::string('twillio-sms.account_sid');

        $auth_token = Config::string('twillio-sms.auth_token');

        $client = new Client($account_sid, $auth_token);

        $client->messages->create($number, [
            'from' => Config::string('twillio-sms.sms_from'),
            'body' => $message,
        ]);

        return true;
    }

    public function getPhoneNumber(): string
    {
        return $this->number;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getSentFrom(): string
    {
        return $this->from;
    }

    /**
     * Prepares the SMS data array for sending a message.
     *
     * This method constructs an array containing the necessary data for sending an SMS,
     * including the sender's number, message body, and risk check settings.
     *
     * @return array An associative array containing the SMS data:
     *               - 'from': The sender's phone number (string)
     *               - 'body': The content of the SMS message (string)
     *               - 'RiskCheck': (Optional) Set to 'disable' if risk check is disabled (string)
     */
    protected function smsData(): array
    {
        $data = [
            'from' => $this->getSentFrom(),
            'body' => $this->getMessage(),
        ];
        $riskCheck = Config::boolean('twillio-sms.risk_check', true);

        if (! $riskCheck) {
            $data['RiskCheck'] = 'disable';
        }
        return $data;
    }

    /**
     * Initializes and returns a Twilio Client instance.
     *
     * This method retrieves the Twilio account SID and auth token from the application's configuration.
     * It then creates and returns a new Twilio Client instance using these credentials.
     *
     * @return Client A Twilio Client instance.
     *
     * @throws ConfigurationException If the Twilio account SID or auth token is not found in the configuration.
     */
    protected function setClient(): Client
    {
        $account_sid = Config::string('twillio-sms.account_sid');
        $auth_token = Config::string('twillio-sms.auth_token');

        return new Client($account_sid, $auth_token);
    }

    /**
     * Validates the recipient's phone number using Twilio's Lookup API.
     *
     * This method retrieves the recipient's phone number from the class properties,
     * fetches the phone number details using Twilio's Lookup API, and performs
     * validation checks based on the provided validation checks.
     *
     * @throws TwilioException If there is an error fetching the phone number details or performing validation.
     */
    protected function validatePhoneNumber(): bool
    {
        $phoneNumberLookUp = $this->client->lookups->v2->phoneNumbers($this->getPhoneNumber())->fetch([
            'fields' => implode(',', $this->validationChecks),
        ]);

        if ($phoneNumberLookUp->phoneNumber === null || $phoneNumberLookUp->valid === false) {
            throw new TwilioException('Invalid phone number.');
        }
        if ($this->validationChecks === []) {
            return true;
        }
        return (new ValidationChecks($phoneNumberLookUp))();
    }
}
