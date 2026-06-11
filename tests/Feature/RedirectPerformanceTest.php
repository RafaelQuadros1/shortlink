<?php

use App\Models\Short;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects in under 500ms', function () {
    $short = Short::factory()->create();

    $start = microtime(true);

    $this->get(route('shorts.redirect', $short->short_code))
        ->assertRedirect($short->url_origin);

    $elapsed = (microtime(true) - $start) * 1000;

    dump("Redirect took {$elapsed}ms");

    $this->assertLessThan(500, $elapsed);
});

it('handles 10 sequential redirects efficiently', function () {
    $shorts = Short::factory()->count(10)->create();

    $start = microtime(true);

    foreach ($shorts as $short) {
        $this->get(route('shorts.redirect', $short->short_code))
            ->assertRedirect($short->url_origin);
    }

    $elapsed = (microtime(true) - $start) * 1000;
    $avg = $elapsed / 10;

    dump("10 redirects took {$elapsed}ms total, {$avg}ms avg");

    $this->assertLessThan(2000, $elapsed);
    $this->assertLessThan(300, $avg);
});
