<?php

use App\Models\ApiKey;
use App\Models\Short;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->key = ApiKey::generateKey();
    ApiKey::factory()->create([
        'user_id' => $this->user->id,
        'key' => $this->key['hashed'],
    ]);
});

describe('GET /api/v1/shorts', function () {
    it('returns paginated shorts for authenticated user', function () {
        $key = ApiKey::generateKey();
        ApiKey::factory()->create([
            'user_id' => $this->user->id,
            'key' => $key['hashed'],
        ]);

        Short::factory()->count(3)->create(['user_id' => $this->user->id]);

        $this->getJson('/api/v1/shorts', [
            'X-API-Key' => $key['plain'],
        ])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'url_origin', 'short_code', 'short_url'],
                ],
            ]);
    });

    it('returns 401 when API key is not provided', function () {
        $this->getJson('/api/v1/shorts')
            ->assertUnauthorized()
            ->assertJsonStructure(['message']);
    });

    it('returns 401 when API key is invalid', function () {
        $this->getJson('/api/v1/shorts', [
            'X-API-Key' => 'sk_invalidkey12345678901234567890',
        ])
            ->assertUnauthorized();
    });

    it('returns 401 when API key is expired', function () {
        $key = ApiKey::generateKey();
        ApiKey::factory()->create([
            'user_id' => $this->user->id,
            'key' => $key['hashed'],
            'expires_at' => now()->subDay(),
        ]);

        $this->getJson('/api/v1/shorts', [
            'X-API-Key' => $key['plain'],
        ])
            ->assertUnauthorized();
    });

    it('only returns shorts belonging to the authenticated user', function () {
        $otherUser = User::factory()->create();
        $otherKey = ApiKey::generateKey();
        ApiKey::factory()->create([
            'user_id' => $otherUser->id,
            'key' => $otherKey['hashed'],
        ]);

        Short::factory()->count(3)->create(['user_id' => $this->user->id]);
        Short::factory()->count(2)->create(['user_id' => $otherUser->id]);

        $this->getJson('/api/v1/shorts', [
            'X-API-Key' => $this->key['plain'],
        ])
            ->assertOk()
            ->assertJsonCount(3, 'data');
    });

    it('returns paginated results', function () {
        Short::factory()->count(15)->create(['user_id' => $this->user->id]);

        $this->getJson('/api/v1/shorts', [
            'X-API-Key' => $this->key['plain'],
        ])
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ])
            ->assertJsonCount(10, 'data');
    });
});

describe('POST /api/v1/shorts', function () {
    it('creates a short link and returns 201', function () {
        $key = ApiKey::generateKey();
        ApiKey::factory()->create([
            'user_id' => $this->user->id,
            'key' => $key['hashed'],
        ]);

        $this->postJson('/api/v1/shorts', [
            'url_origin' => 'https://example.com',
        ], [
            'X-API-Key' => $key['plain'],
        ])
            ->assertCreated()
            ->assertJsonStructure([
                'data' => ['id', 'url_origin', 'short_code', 'short_url'],
            ]);

        $this->assertDatabaseHas('shorts', [
            'user_id' => $this->user->id,
            'url_origin' => 'https://example.com',
        ]);
    });

    it('validates url_origin is required', function () {
        $key = ApiKey::generateKey();
        ApiKey::factory()->create([
            'user_id' => $this->user->id,
            'key' => $key['hashed'],
        ]);

        $this->postJson('/api/v1/shorts', [], [
            'X-API-Key' => $key['plain'],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('url_origin');
    });

    it('validates url_origin must be a valid URL', function () {
        $key = ApiKey::generateKey();
        ApiKey::factory()->create([
            'user_id' => $this->user->id,
            'key' => $key['hashed'],
        ]);

        $this->postJson('/api/v1/shorts', [
            'url_origin' => 'not-a-url',
        ], [
            'X-API-Key' => $key['plain'],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('url_origin');
    });
});
