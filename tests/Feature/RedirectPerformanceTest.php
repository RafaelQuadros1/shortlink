<?php

use App\Models\Short;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

it('redirects in under 500ms', function () {
    $short = Short::factory()->create();

    $start = microtime(true);

    $this->get(route('shorts.redirect', $short->short_code))
        ->assertRedirect($short->url_origin);

    $elapsed = (microtime(true) - $start) * 1000;

    dump('Redirect took '.number_format($elapsed, 2).'ms');

    $this->assertLessThan(500, $elapsed);
});

it('handles 2000 sequential redirects efficiently', function () {
    RateLimiter::for('redirect', fn () => Limit::none());

    $shorts = Short::factory()->count(2000)->create();

    $start = microtime(true);

    foreach ($shorts as $short) {
        $this->get(route('shorts.redirect', $short->short_code))
            ->assertRedirect($short->url_origin);
    }

    $elapsed = (microtime(true) - $start) * 1000;
    $avg = $elapsed / 2000;

    dump('2000 redirects took '.number_format($elapsed, 2).'ms total, '.number_format($avg, 2).'ms avg');

    $this->assertLessThan(6000, $elapsed);
    $this->assertLessThan(5, $avg);
});
