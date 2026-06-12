<?php

use App\Models\Short;
use App\Models\User;
use App\Services\EncryptId;

it('belongs to a user', function () {
    $user = User::factory()->create();
    $short = Short::factory()->for($user)->create();

    expect($short->user)->toBeInstanceOf(User::class);
    expect($short->user->id)->toBe($user->id);
});

it('has many clicks', function () {
    $short = Short::factory()->create();

    expect($short->clicks)->toHaveCount(0);

    $short->clicks()->create([
        'ip_address' => hash('sha256', '127.0.0.1'),
        'user_agent' => 'Mozilla/5.0',
        'clicked_at' => now(),
    ]);

    expect($short->fresh()->clicks)->toHaveCount(1);
});

it('generates a short code on creation via observer', function () {
    $short = Short::factory()->create();

    expect($short->short_code)->not->toBeNull();
    expect($short->short_code)->toBeString()->not->toBeEmpty();
});

it('returns the correct short_url attribute', function () {
    $short = Short::factory()->create();

    expect($short->short_url)->toBe(route('shorts.redirect', $short->short_code));
});

it('returns an encrypted ID attribute', function () {
    $short = Short::factory()->create();

    $encryptedId = $short->encryptedId;
    $decrypted = (new EncryptId)->decrypt($encryptedId);

    expect($encryptedId)->toBeString();
    expect($decrypted)->toBe($short->id);
});

it('resolves route binding via encrypted ID', function () {
    $short = Short::factory()->create();
    $encryptedId = (new EncryptId)->encrypt($short->id);

    $resolved = (new Short)->resolveRouteBinding($encryptedId);

    expect($resolved)->not->toBeNull();
    expect($resolved->id)->toBe($short->id);
});

it('returns null for invalid encrypted route binding', function () {
    $resolved = (new Short)->resolveRouteBinding('invalid-encrypted-value');

    expect($resolved)->toBeNull();
});

it('hides timestamps in hidden attributes', function () {
    $short = Short::factory()->create();
    $array = $short->toArray();

    expect($array)->not->toHaveKey('created_at');
    expect($array)->not->toHaveKey('updated_at');
});

it('has correct fillable attributes', function () {
    $short = new Short;

    expect($short->getFillable())->toContain('user_id');
    expect($short->getFillable())->toContain('url_origin');
    expect($short->getFillable())->toContain('short_code');
});
