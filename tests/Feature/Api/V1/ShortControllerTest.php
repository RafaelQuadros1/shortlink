<?php

use App\Models\ApiKey;
use App\Models\Short;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->key = ApiKey::generateKey();
    ApiKey::factory()->create([
        'user_id' => $this->user->id,
        'key' => $this->key['hashed'],
        'key_lookup' => $this->key['key_lookup'],
    ]);
});

describe('GET /api/v1/shorts', function () {
    it('returns paginated shorts for authenticated user', function () {
        $key = ApiKey::generateKey();
        ApiKey::factory()->create([
            'user_id' => $this->user->id,
            'key' => $key['hashed'],
            'key_lookup' => $key['key_lookup'],
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
            'key_lookup' => $key['key_lookup'],
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
            'key_lookup' => $otherKey['key_lookup'],
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

    it('filters shorts by search term', function () {
        Short::factory()->create(['user_id' => $this->user->id, 'url_origin' => 'https://google.com']);
        Short::factory()->create(['user_id' => $this->user->id, 'url_origin' => 'https://github.com']);

        $this->getJson('/api/v1/shorts?search=google', [
            'X-API-Key' => $this->key['plain'],
        ])
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.url_origin', 'https://google.com');
    });

    it('sorts shorts by created_at', function () {
        Short::factory()->create(['user_id' => $this->user->id, 'created_at' => now()->subDay()]);
        Short::factory()->create(['user_id' => $this->user->id, 'created_at' => now()]);

        $this->getJson('/api/v1/shorts?sort=created_at&order=desc', [
            'X-API-Key' => $this->key['plain'],
        ])
            ->assertOk()
            ->assertJsonCount(2, 'data');
    });

    it('limits per_page to minimum of 1', function () {
        Short::factory()->count(3)->create(['user_id' => $this->user->id]);

        $this->getJson('/api/v1/shorts?per_page=-1', [
            'X-API-Key' => $this->key['plain'],
        ])
            ->assertOk()
            ->assertJsonCount(1, 'data');
    });
});

describe('POST /api/v1/shorts', function () {
    it('creates a short link and returns 201', function () {
        $key = ApiKey::generateKey();
        ApiKey::factory()->create([
            'user_id' => $this->user->id,
            'key' => $key['hashed'],
            'key_lookup' => $key['key_lookup'],
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
            'key_lookup' => $key['key_lookup'],
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
            'key_lookup' => $key['key_lookup'],
        ]);

        $this->postJson('/api/v1/shorts', [
            'url_origin' => 'not-a-url',
        ], [
            'X-API-Key' => $key['plain'],
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('url_origin');
    });

    it('creates activity log when short is created', function () {
        $key = ApiKey::generateKey();
        ApiKey::factory()->create([
            'user_id' => $this->user->id,
            'key' => $key['hashed'],
            'key_lookup' => $key['key_lookup'],
        ]);

        $this->postJson('/api/v1/shorts', [
            'url_origin' => 'https://example.com',
        ], [
            'X-API-Key' => $key['plain'],
        ])
            ->assertCreated();

        $this->assertDatabaseHas('activity_log', [
            'description' => 'Short link created via API',
            'event' => 'created',
        ]);
    });
});

describe('DELETE /api/v1/shorts/{id}', function () {
    it('deletes a short link and returns 204', function () {
        $short = Short::factory()->create(['user_id' => $this->user->id]);

        $this->deleteJson("/api/v1/shorts/{$short->encrypted_id}", [], [
            'X-API-Key' => $this->key['plain'],
        ])
            ->assertNoContent();

        $this->assertDatabaseMissing('shorts', [
            'id' => $short->id,
        ]);
    });

    it('returns 404 when short does not exist', function () {
        $encryptedId = encrypt('999999');

        $this->deleteJson("/api/v1/shorts/{$encryptedId}", [], [
            'X-API-Key' => $this->key['plain'],
        ])
            ->assertNotFound();
    });

    it('returns 404 when short belongs to another user', function () {
        $otherUser = User::factory()->create();
        $short = Short::factory()->create(['user_id' => $otherUser->id]);

        $this->deleteJson("/api/v1/shorts/{$short->encrypted_id}", [], [
            'X-API-Key' => $this->key['plain'],
        ])
            ->assertNotFound();
    });

    it('creates activity log when short is deleted', function () {
        $short = Short::factory()->create(['user_id' => $this->user->id]);

        $this->deleteJson("/api/v1/shorts/{$short->encrypted_id}", [], [
            'X-API-Key' => $this->key['plain'],
        ])
            ->assertNoContent();

        $this->assertDatabaseHas('activity_log', [
            'description' => 'Short link deleted via API',
            'event' => 'deleted',
        ]);
    });
});
