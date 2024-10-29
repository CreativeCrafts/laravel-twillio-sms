<?php

declare(strict_types=1);

use CreativeCrafts\LaravelTwillioSms\Services\ValidateLineTypeIntelligence;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Lookups\V2\PhoneNumberInstance;

covers(ValidateLineTypeIntelligence::class);

it('does not throw an exception for mobile type', function () {
    $phoneNumberLookUp = Mockery::mock(PhoneNumberInstance::class);
    $phoneNumberLookUp->lineTypeIntelligence = ['type' => 'mobile'];

    $validateLineTypeIntelligence = new ValidateLineTypeIntelligence($phoneNumberLookUp);

    expect(fn () => $validateLineTypeIntelligence())->not->toThrow(TwilioException::class);
});

it('does not throw an exception for unknown type', function () {
    $phoneNumberLookUp = Mockery::mock(PhoneNumberInstance::class);
    $phoneNumberLookUp->lineTypeIntelligence = ['type' => 'unknown'];

    $validateLineTypeIntelligence = new ValidateLineTypeIntelligence($phoneNumberLookUp);

    expect(fn () => $validateLineTypeIntelligence())->not->toThrow(TwilioException::class);
});

it('throws an exception for non-mobile type', function () {
    $phoneNumberLookUp = Mockery::mock(PhoneNumberInstance::class);
    $phoneNumberLookUp->lineTypeIntelligence = ['type' => 'landline'];

    $validateLineTypeIntelligence = new ValidateLineTypeIntelligence($phoneNumberLookUp);

    expect(fn () => $validateLineTypeIntelligence())->toThrow(TwilioException::class);
});

it('throws an exception for an invalid lineTypeIntelligence structure', function () {
    $phoneNumberLookUp = Mockery::mock(PhoneNumberInstance::class);
    $phoneNumberLookUp->lineTypeIntelligence = []; // Missing 'type' key

    $validateLineTypeIntelligence = new ValidateLineTypeIntelligence($phoneNumberLookUp);

    expect(fn () => $validateLineTypeIntelligence())->toThrow(ErrorException::class);
});

it('throws an exception if lineTypeIntelligence is null', function () {
    $phoneNumberLookUp = Mockery::mock(PhoneNumberInstance::class);
    $phoneNumberLookUp->lineTypeIntelligence = null; // Simulating an invalid case

    $validateLineTypeIntelligence = new ValidateLineTypeIntelligence($phoneNumberLookUp);

    expect(fn () => $validateLineTypeIntelligence())->toThrow(ErrorException::class);
});
