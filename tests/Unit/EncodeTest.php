<?php

use App\Services\Decode;
use App\Services\Encode;

it('encodes zero to the first character of the alphabet', function () {
    $encode = new Encode;
    $code = $encode->code(0);

    expect($code)->toBeString()->not->toBeEmpty();
});

it('encodes positive integers to strings', function () {
    $encode = new Encode;

    expect($encode->code(1))->toBeString()->not->toBeEmpty();
    expect($encode->code(62))->toBeString()->not->toBeEmpty();
    expect($encode->code(999))->toBeString()->not->toBeEmpty();
    expect($encode->code(12345))->toBeString()->not->toBeEmpty();
});

it('throws exception for negative numbers', function () {
    $encode = new Encode;
    $encode->code(-1);
})->throws(InvalidArgumentException::class, 'Number must be non-negative.');

it('produces codes that are reversible via Decode', function () {
    $encode = new Encode;
    $decode = new Decode;

    foreach ([0, 1, 42, 62, 100, 999, 12345, 999999] as $number) {
        $code = $encode->code($number);
        $decoded = $decode->decode($code);

        expect($decoded)->toBe($number);
    }
});

it('encodes consistently for the same input', function () {
    $encode = new Encode;

    $code1 = $encode->code(42);
    $code2 = $encode->code(42);

    expect($code1)->toBe($code2);
});

it('encodes different numbers to different codes', function () {
    $encode = new Encode;

    $code1 = $encode->code(1);
    $code2 = $encode->code(2);

    expect($code1)->not->toBe($code2);
});

it('handles large numbers without overflow', function () {
    $encode = new Encode;
    $decode = new Decode;

    $largeNumber = 1_000_000_000;
    $code = $encode->code($largeNumber);
    $decoded = $decode->decode($code);

    expect($decoded)->toBe($largeNumber);
});
