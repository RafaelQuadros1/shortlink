<?php

use App\Models\Short;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Short Expiration', function () {
    it('sets expires_at for guest-created links', function () {
        $short = Short::factory()->create([
            'user_id' => null,
            'short_code' => null,
        ]);

        expect($short->fresh()->expires_at)->not->toBeNull();
        expect($short->fresh()->expires_at)->toBeGreaterThanOrEqual(now()->addDays(29));
        expect($short->fresh()->expires_at)->toBeLessThanOrEqual(now()->addDays(31));
    });

    it('does not set expires_at for authenticated user links', function () {
        $user = User::factory()->create();
        $short = Short::factory()->create([
            'user_id' => $user->id,
            'short_code' => null,
        ]);

        expect($short->fresh()->expires_at)->toBeNull();
    });

    it('returns expired status for past expires_at', function () {
        $short = Short::factory()->create([
            'expires_at' => now()->subDay(),
        ]);

        expect($short->isExpired())->toBeTrue();
    });

    it('returns active status for future expires_at', function () {
        $short = Short::factory()->create([
            'expires_at' => now()->addDays(30),
        ]);

        expect($short->isExpired())->toBeFalse();
    });

    it('returns active status for null expires_at', function () {
        $short = Short::factory()->create([
            'expires_at' => null,
        ]);

        expect($short->isExpired())->toBeFalse();
    });

    it('scope active filters out expired links', function () {
        Short::factory()->create(['expires_at' => now()->subDay()]);
        Short::factory()->create(['expires_at' => now()->addDays(30)]);
        Short::factory()->create(['expires_at' => null]);

        $activeShorts = Short::active()->count();

        expect($activeShorts)->toBe(2);
    });

    it('redirect returns not-found for expired link', function () {
        $short = Short::factory()->create([
            'short_code' => null,
            'expires_at' => now()->subDay(),
        ]);

        $this->get("/{$short->short_code}")
            ->assertRedirect(route('shorts.not-found'));
    });

    it('redirect works for active link', function () {
        $short = Short::factory()->create([
            'short_code' => null,
            'url_origin' => 'https://example.com',
            'expires_at' => now()->addDays(30),
        ]);

        $this->get("/{$short->short_code}")
            ->assertRedirect('https://example.com');
    });

    it('cleanup command deletes expired links', function () {
        Short::factory()->create([
            'expires_at' => now()->subDay(),
        ]);
        Short::factory()->create([
            'expires_at' => now()->addDays(30),
        ]);

        $this->artisan('shorts:cleanup')
            ->assertExitCode(0);

        expect(Short::count())->toBe(1);
    });

    it('index only shows active links for authenticated user', function () {
        $user = User::factory()->create();

        $activeShort = Short::factory()->create([
            'user_id' => $user->id,
            'expires_at' => null,
        ]);
        $expiredShort = Short::factory()->create([
            'user_id' => $user->id,
            'expires_at' => now()->subDay(),
        ]);

        actingAs($user)
            ->get('/shorts')
            ->assertOk()
            ->assertSee($activeShort->short_code)
            ->assertDontSee($expiredShort->url_origin);
    });
});
