<?php

use App\Models\Short;
use App\Models\User;
use App\Policies\ShortPolicy;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
});

it('allows owner to view their short', function () {
    $short = Short::factory()->for($this->user)->create();

    expect((new ShortPolicy)->view($this->user, $short))->toBeTrue();
});

it('prevents non-owner from viewing short', function () {
    $short = Short::factory()->for($this->user)->create();

    expect((new ShortPolicy)->view($this->otherUser, $short))->toBeFalse();
});

it('allows anyone to create shorts', function () {
    expect((new ShortPolicy)->create($this->user))->toBeTrue();
    expect((new ShortPolicy)->create($this->otherUser))->toBeTrue();
});

it('allows owner to update their short', function () {
    $short = Short::factory()->for($this->user)->create();

    expect((new ShortPolicy)->update($this->user, $short))->toBeTrue();
});

it('prevents non-owner from updating short', function () {
    $short = Short::factory()->for($this->user)->create();

    expect((new ShortPolicy)->update($this->otherUser, $short))->toBeFalse();
});

it('allows owner to view any shorts', function () {
    expect((new ShortPolicy)->viewAny($this->user))->toBeTrue();
});

it('allows owner to delete their short', function () {
    $short = Short::factory()->for($this->user)->create();

    expect((new ShortPolicy)->delete($this->user, $short))->toBeTrue();
});

it('prevents non-owner from deleting short', function () {
    $short = Short::factory()->for($this->user)->create();

    expect((new ShortPolicy)->delete($this->otherUser, $short))->toBeFalse();
});
