<?php

use App\Jobs\RecordClickJob;
use App\Models\Click;
use App\Models\Short;

it('creates a click record when job is handled', function () {
    $short = Short::factory()->create();
    $ipHash = hash('sha256', '127.0.0.1');

    $job = new RecordClickJob(
        shortId: $short->id,
        ipAddress: $ipHash,
        userAgent: 'Mozilla/5.0',
    );

    $job->handle();

    $this->assertDatabaseHas('clicks', [
        'short_id' => $short->id,
        'ip_address' => $ipHash,
        'user_agent' => 'Mozilla/5.0',
    ]);
});

it('handles null ip_address', function () {
    $short = Short::factory()->create();

    $job = new RecordClickJob(
        shortId: $short->id,
        ipAddress: null,
        userAgent: 'Mozilla/5.0',
    );

    $job->handle();

    $this->assertDatabaseHas('clicks', [
        'short_id' => $short->id,
        'ip_address' => null,
    ]);
});

it('handles null user_agent', function () {
    $short = Short::factory()->create();

    $job = new RecordClickJob(
        shortId: $short->id,
        ipAddress: hash('sha256', '127.0.0.1'),
        userAgent: null,
    );

    $job->handle();

    $this->assertDatabaseHas('clicks', [
        'short_id' => $short->id,
        'user_agent' => null,
    ]);
});

it('sets clicked_at to current time', function () {
    $short = Short::factory()->create();

    $job = new RecordClickJob(
        shortId: $short->id,
        ipAddress: hash('sha256', '127.0.0.1'),
        userAgent: 'Mozilla/5.0',
    );

    $job->handle();

    $click = Click::where('short_id', $short->id)->first();
    expect($click->clicked_at)->not->toBeNull();
});

it('can be dispatched to the queue', function () {
    Queue::fake();

    $short = Short::factory()->create();

    RecordClickJob::dispatch(
        shortId: $short->id,
        ipAddress: hash('sha256', '127.0.0.1'),
        userAgent: 'Mozilla/5.0',
    );

    Queue::assertPushed(RecordClickJob::class);
});

it('has correct retry configuration', function () {
    $job = new RecordClickJob(shortId: 1, ipAddress: null, userAgent: null);

    expect($job->tries)->toBe(3);
    expect($job->backoff)->toBe(5);
});
