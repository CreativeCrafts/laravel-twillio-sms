<?php

use creativeCrafts\LaravelTwillioSms\LaravelTwillioSms;

it('can send an sms', function () {
    $number = '+2347030000000';
    $message = 'This is a test message';

    $mock = Mockery::mock(app(LaravelTwillioSms::class))
        ->shouldReceive('sendSms')
        ->once()
        ->with($number, $message)
        ->andReturn(true)
        ->getMock();

    expect($mock->sendSms($number, $message))->toBeTrue();
});