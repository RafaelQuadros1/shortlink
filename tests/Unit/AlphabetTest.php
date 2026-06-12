<?php

use App\Services\Alphabet;

it('returns a 62-character alphabet', function () {
    $alphabet = new Alphabet;

    expect($alphabet->getEncrypted())->toHaveLength(62);
    expect($alphabet->getOriginal())->toHaveLength(62);
});

it('returns the original alphabet in correct order', function () {
    $alphabet = new Alphabet;

    expect($alphabet->getOriginal())->toBe('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
});

it('returns a shuffled alphabet different from the original', function () {
    $alphabet = new Alphabet;

    expect($alphabet->getEncrypted())->not->toBe($alphabet->getOriginal());
});

it('returns the same shuffled alphabet on multiple calls (cached)', function () {
    $alphabet = new Alphabet;

    $first = $alphabet->getEncrypted();
    $second = $alphabet->getEncrypted();

    expect($first)->toBe($second);
});

it('contains all alphanumeric characters in the original alphabet', function () {
    $alphabet = new Alphabet;
    $original = $alphabet->getOriginal();

    expect($original)->toContain('0');
    expect($original)->toContain('9');
    expect($original)->toContain('a');
    expect($original)->toContain('z');
    expect($original)->toContain('A');
    expect($original)->toContain('Z');
});
