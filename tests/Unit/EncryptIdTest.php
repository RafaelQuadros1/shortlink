<?php

use App\Services\EncryptId;

it('encrypts and decrypts an integer ID', function () {
    $service = new EncryptId;

    $encrypted = $service->encrypt(42);
    $decrypted = $service->decrypt($encrypted);

    expect($decrypted)->toBe(42);
});

it('produces different encrypted values for different IDs', function () {
    $service = new EncryptId;

    $encrypted1 = $service->encrypt(1);
    $encrypted2 = $service->encrypt(2);

    expect($encrypted1)->not->toBe($encrypted2);
});

it('produces encrypted values that are strings', function () {
    $service = new EncryptId;

    $encrypted = $service->encrypt(100);

    expect($encrypted)->toBeString();
});

it('handles zero as a valid ID', function () {
    $service = new EncryptId;

    $encrypted = $service->encrypt(0);
    $decrypted = $service->decrypt($encrypted);

    expect($decrypted)->toBe(0);
});

it('handles large IDs', function () {
    $service = new EncryptId;

    $largeId = 999_999_999;
    $encrypted = $service->encrypt($largeId);
    $decrypted = $service->decrypt($encrypted);

    expect($decrypted)->toBe($largeId);
});

it('round-trips through encrypt and decrypt for multiple IDs', function () {
    $service = new EncryptId;

    foreach ([1, 10, 50, 100, 500, 1000] as $id) {
        $encrypted = $service->encrypt($id);
        $decrypted = $service->decrypt($encrypted);

        expect($decrypted)->toBe($id);
    }
});
