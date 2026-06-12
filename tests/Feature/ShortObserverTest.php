<?php

use App\Models\Short;
use App\Services\Encode;
use Illuminate\Support\Facades\Cache;

it('auto-generates short code on creation when null', function () {
    $short = Short::factory()->create(['short_code' => null]);

    expect($short->fresh()->short_code)->not->toBeNull();
    expect($short->fresh()->short_code)->toBeString()->not->toBeEmpty();
});

it('does not overwrite existing short code on creation', function () {
    $short = Short::factory()->create(['short_code' => 'custom']);

    expect($short->fresh()->short_code)->toBe('custom');
});

it('clears cache on update', function () {
    Cache::shouldReceive('forget')
        ->once()
        ->with('short:1');

    $short = Short::factory()->createQuietly(['id' => 1]);
    $short->update(['url_origin' => 'https://updated.com']);
});

it('clears cache on deletion', function () {
    Cache::shouldReceive('forget')
        ->once()
        ->with('short:1');

    $short = Short::factory()->createQuietly(['id' => 1]);
    $short->delete();
});

it('generates unique short codes for different shorts', function () {
    $short1 = Short::factory()->create();
    $short2 = Short::factory()->create();

    expect($short1->short_code)->not->toBe($short2->short_code);
});

it('generates short code using Encode service', function () {
    $short = Short::factory()->create();

    $code = (new Encode)->code($short->id);
    expect($short->fresh()->short_code)->toBe($code);
});
