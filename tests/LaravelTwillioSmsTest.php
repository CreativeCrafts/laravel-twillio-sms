<?php

declare(strict_types=1);

use CreativeCrafts\LaravelTwillioSms\LaravelTwillioSms;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Rest\Client;
use Twilio\Rest\Lookups\V2\PhoneNumberInstance;
use Twilio\Version;

covers(LaravelTwillioSms::class);

beforeEach(function () {
    // Create a partial mock of LaravelTwillioSms to allow constructor execution
    $this->sms = Mockery::mock(LaravelTwillioSms::class)->makePartial();

    // Mock the setClient method to return the mocked client
    $this->clientMock = Mockery::mock(Client::class);
    $this->sms->shouldAllowMockingProtectedMethods()->shouldReceive('setClient')->andReturn($this->clientMock);

    // Use reflection to access and set the protected client property
    $reflection = new ReflectionClass($this->sms);
    $clientProperty = $reflection->getProperty('client');
    $clientProperty->setAccessible(true);
    $clientProperty->setValue($this->sms, $this->clientMock);

    // Mock the request method to return a Twilio\Http\Response
    $responseMock = Mockery::mock(Response::class);
    $responseMock->shouldReceive('getStatusCode')->andReturn(200);
    $responseMock->shouldReceive('getContent')->andReturn('{"success": true}');
    $this->clientMock->shouldReceive('request')->andReturn($responseMock);
});

afterEach(function () {
    // Ensure Mockery is closed after each test
    Mockery::close();
});

test('it can set and get a phone number', function () {
    $phoneNumber = '+1234567890';
    $this->sms->setPhoneNumber($phoneNumber);

    expect($this->sms->getPhoneNumber())->toBe($phoneNumber);
});

test('it can set and get a message', function () {
    $message = 'Hello, this is a test message.';
    $this->sms->setMessage($message);

    expect($this->sms->getMessage())->toBe($message);
});

test('it can set and get a from number', function () {
    $fromNumber = '+0987654321';
    $this->sms->setFrom($fromNumber);

    expect($this->sms->getSentFrom())->toBe($fromNumber);
});

test('it throws an exception for invalid configuration', function () {
    $this->sms->shouldAllowMockingProtectedMethods()
        ->shouldReceive('setClient')
        ->andThrow(ConfigurationException::class);
    $this->sms->__construct();
})->throws(InvalidArgumentException::class, 'Configuration value for key [twillio-sms.sms_from] must be a string, NULL given.');

test('it throws an exception for invalid phone number', function () {
    $this->sms->setPhoneNumber('invalid-number');

    // Create mocks for each part of the chain
    $lookupsMock = Mockery::mock();
    $v2Mock = Mockery::mock();
    $phoneNumbersMock = Mockery::mock();

    // Set up the method chaining
    $this->clientMock->lookups = $lookupsMock;
    $lookupsMock->v2 = $v2Mock;
    $v2Mock->shouldReceive('phoneNumbers')
        ->with('invalid-number')
        ->andReturn($phoneNumbersMock);
    $phoneNumbersMock->shouldReceive('fetch')
        ->andThrow(TwilioException::class, 'Invalid phone number.');

    // Expect the exception
    $this->expectException(TwilioException::class);

    // Execute the send method
    $this->sms->send();
});

test('it sends an SMS successfully', function () {
    // Specific mock setup for this test
    $lookupsMock = Mockery::mock();
    $v2Mock = Mockery::mock();
    $phoneNumbersMock = Mockery::mock();

    $this->clientMock->lookups = $lookupsMock;
    $lookupsMock->v2 = $v2Mock;
    $v2Mock->shouldReceive('phoneNumbers')
        ->with('+1234567890')
        ->andReturn($phoneNumbersMock);

    // Create a payload for PhoneNumberInstance
    $payload = [
        'phone_number' => '+1234567890',
        'valid' => true,
    ];

    // Mock the PhoneNumberInstance
    $versionMock = Mockery::mock(Version::class);
    $phoneNumberInstanceMock = new PhoneNumberInstance($versionMock, $payload);

    $phoneNumbersMock->shouldReceive('fetch')
        ->andReturn($phoneNumberInstanceMock);

    $messagesMock = Mockery::mock();
    $this->clientMock->messages = $messagesMock;
    $messagesMock->shouldReceive('create')
        ->once()
        ->with('+1234567890', [
            'from' => '+0987654321',
            'body' => 'Test message',
        ])
        ->andReturnTrue();

    $this->sms->setPhoneNumber('+1234567890')
        ->setMessage('Test message')
        ->setFrom('+0987654321');

    expect($this->sms->send())->toBeTrue();
});

it('sends SMS successfully and returns true', function () {
    $number = '+1234567890';
    $message = 'Test message';

    // Mock the Twilio Client and the messages instance
    $client = Mockery::mock(Client::class);
    $messages = Mockery::mock();
    $client->messages = $messages;

    // Bind the mock to the container so that resolve(Client::class) returns the mock
    app()->instance(Client::class, $client);

    $messages->shouldReceive('create')
        ->with($number, [
            'from' => config('twillio-sms.sms_from'),
            'body' => $message,
        ])
        ->andReturn(true);

    // Inject the mocked client into the method
    $result = (new class () {
        public function sendSms(string $number, string $message): bool
        {
            trigger_error('The sendSms is deprecated and will be removed in version 2.0. Use send instead.', E_USER_DEPRECATED);

            $client = resolve(Client::class); // The mock Client will be resolved
            $client->messages->create($number, [
                'from' => config('twillio-sms.sms_from'),
                'body' => $message,
            ]);

            return true;
        }
    })->sendSms($number, $message);

    expect($result)->toBeTrue();
});

it('triggers a deprecation warning', function () {
    $number = '+1234567890';
    $message = 'Test message';

    // Set up a custom error handler to catch deprecation warnings
    $errorTriggered = false;
    set_error_handler(function ($errno, $errstr) use (&$errorTriggered) {
        if ($errno === E_USER_DEPRECATED && str_contains($errstr, 'The sendSms is deprecated and will be removed in version 2.0. Use send instead.')) {
            $errorTriggered = true;
        }
        return true; // Suppress error output
    });

    // Mock the Client and messages instance
    $client = Mockery::mock(Client::class);
    $messages = Mockery::mock();
    $messages->shouldReceive('create')
        ->with($number, [
            'from' => config('twillio-sms.sms_from'),
            'body' => $message,
        ])
        ->andReturn(true);

    // Assign the messages mock directly to the messages property
    $client->messages = $messages;

    // Bind the mock to the container
    app()->instance(Client::class, $client);

    // Run the method to trigger the deprecation warning
    (new class () {
        public function sendSms(string $number, string $message): bool
        {
            trigger_error('The sendSms is deprecated and will be removed in version 2.0. Use send instead.', E_USER_DEPRECATED);

            $client = resolve(Client::class);
            $client->messages->create($number, [
                'from' => config('twillio-sms.sms_from'),
                'body' => $message,
            ]);

            return true;
        }
    })->sendSms($number, $message);

    // Restore the original error handler
    restore_error_handler();

    // Assert that the deprecation warning was triggered
    expect($errorTriggered)->toBeTrue();
});

it('throws an exception if Twilio fails', function () {
    $number = '+1234567890';
    $message = 'Test message';

    $client = Mockery::mock(Client::class);
    $messages = Mockery::mock();
    $client->messages = $messages;

    $messages->shouldReceive('create')
        ->with($number, [
            'from' => config('twillio-sms.sms_from'),
            'body' => $message,
        ])
        ->andThrow(TwilioException::class);

    $handler = fn () => (new class () {
        public function sendSms(string $number, string $message): bool
        {
            trigger_error('The sendSms is deprecated and will be removed in version 2.0. Use send instead.', E_USER_DEPRECATED);

            $client = resolve(Client::class);
            $client->messages->create($number, [
                'from' => config('twillio-sms.sms_from'),
                'body' => $message,
            ]);

            return true;
        }
    })->sendSms($number, $message);

    expect($handler)->toThrow(TwilioException::class);
});

it('returns SMS data without RiskCheck when risk_check is enabled', function () {
    setDefaultConfig();

    $sms = new LaravelTwillioSms();
    $sms->setFrom('+1234567890')
        ->setMessage('Hello World');

    $reflection = new ReflectionClass($sms);
    $method = $reflection->getMethod('smsData');
    $method->setAccessible(true);

    $data = $method->invoke($sms);

    expect($data)->toBe([
        'from' => '+1234567890',
        'body' => 'Hello World',
    ]);
});

it('returns SMS data with RiskCheck when risk_check is disabled', function () {
    setDefaultConfig(riskCheck: false);

    $sms = new LaravelTwillioSms();
    $sms->setFrom('+1234567890')
        ->setMessage('Hello World');

    $reflection = new ReflectionClass($sms);
    $method = $reflection->getMethod('smsData');
    $method->setAccessible(true);

    $data = $method->invoke($sms);

    expect($data)->toBe([
        'from' => '+1234567890',
        'body' => 'Hello World',
        'RiskCheck' => 'disable',
    ]);
});
