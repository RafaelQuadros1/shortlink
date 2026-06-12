<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects to not-found when url has underscore in code', function () {
    $this->get('/abc_def')
        ->assertRedirect(route('shorts.not-found'));
});

it('redirects to not-found when url has dash in code', function () {
    $this->get('/a-b-c')
        ->assertRedirect(route('shorts.not-found'));
});

it('redirects to not-found when url has exclamation in code', function () {
    $this->get('/a!b')
        ->assertRedirect(route('shorts.not-found'));
});

it('redirects to not-found when url is completely invalid', function () {
    $this->get('/!!!')
        ->assertRedirect(route('shorts.not-found'));
});
