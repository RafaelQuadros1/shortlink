<?php

use App\Models\Short;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('ShortController@index', function () {
    it('returns index view', function () {
        actingAs($this->user)
            ->get('/shorts')
            ->assertOk();
    });
});

describe('ShortController@store', function () {
    it('creates a new short and returns JSON', function () {
        actingAs($this->user)
            ->postJson('/shorts', ['url_origin' => 'https://example.com'])
            ->assertCreated()
            ->assertJsonStructure(['short_url']);
    });

    it('validates url_origin is required', function () {
        actingAs($this->user)
            ->post('/shorts', [])
            ->assertSessionHasErrors('url_origin');
    });
});

describe('ShortController@redirect', function () {
    it('redirects to the original URL for a valid short code', function () {
        $short = Short::factory()->create(['url_origin' => 'https://example.com']);

        $this->get("/{$short->short_code}")
            ->assertRedirect('https://example.com');
    });

    it('redirects to not-found page for invalid code', function () {
        $this->get('/invalid_code_xyz')
            ->assertRedirect(route('shorts.not-found'));
    });
});
