<?php

use App\Models\Short;
use App\Models\User;
use App\Services\EncryptId;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('validates url_origin is required on store', function () {
    actingAs($this->user)
        ->post('/shorts', [])
        ->assertSessionHasErrors('url_origin')
        ->assertSessionHasErrors('url_origin', 'O campo URL de origem é obrigatório.');
});

it('validates url_origin must be a valid URL on store', function () {
    actingAs($this->user)
        ->post('/shorts', ['url_origin' => 'not-a-url'])
        ->assertSessionHasErrors('url_origin')
        ->assertSessionHasErrors('url_origin', 'O campo URL de origem deve ser uma URL válida.');
});

it('validates url_origin max 4096 characters on store', function () {
    actingAs($this->user)
        ->post('/shorts', ['url_origin' => 'https://example.com/'.str_repeat('a', 4097)])
        ->assertSessionHasErrors('url_origin')
        ->assertSessionHasErrors('url_origin', 'O campo URL de origem não pode exceder 4096 caracteres.');
});

it('accepts valid URL on store', function () {
    actingAs($this->user)
        ->post('/shorts', ['url_origin' => 'https://example.com'])
        ->assertRedirect('/shorts');
});

it('validates url_origin is required on update', function () {
    $short = Short::factory()->for($this->user)->create();
    $encryptedId = (new EncryptId)->encrypt($short->id);

    actingAs($this->user)
        ->put("/shorts/{$encryptedId}", [])
        ->assertSessionHasErrors('url_origin')
        ->assertSessionHasErrors('url_origin', 'A URL de destino é obrigatória.');
});

it('validates url_origin must be a valid URL on update', function () {
    $short = Short::factory()->for($this->user)->create();
    $encryptedId = (new EncryptId)->encrypt($short->id);

    actingAs($this->user)
        ->put("/shorts/{$encryptedId}", ['url_origin' => 'not-a-url'])
        ->assertSessionHasErrors('url_origin')
        ->assertSessionHasErrors('url_origin', 'Informe uma URL válida.');
});

it('validates url_origin max 4096 characters on update', function () {
    $short = Short::factory()->for($this->user)->create();
    $encryptedId = (new EncryptId)->encrypt($short->id);

    actingAs($this->user)
        ->put("/shorts/{$encryptedId}", ['url_origin' => 'https://example.com/'.str_repeat('a', 4097)])
        ->assertSessionHasErrors('url_origin')
        ->assertSessionHasErrors('url_origin', 'A URL não pode ter mais de 4096 caracteres.');
});
