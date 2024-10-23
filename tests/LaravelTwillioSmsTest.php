<?php

declare(strict_types=1);

use CreativeCrafts\LaravelTwillioSms\LaravelTwillioSms;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Rest\Client;
use Twilio\Rest\Lookups\V2\PhoneNumberInstance;
use Twilio\Version;

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
})->throws(TypeError::class, 'Cannot assign null to property CreativeCrafts\LaravelTwillioSms\LaravelTwillioSms::$from of type string');

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
