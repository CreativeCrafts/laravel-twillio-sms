<?php

use creativeCrafts\LaravelTwillioSms\LaravelTwillioSms;

it('can send an sms', function () {
    $mock = Mockery::mock(LaravelTwillioSms::class);
    $mock->shouldReceive('sendSms')->with('+2347030000000', 'Hello World')->andReturn();
});
