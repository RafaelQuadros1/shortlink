<?php

use App\Models\Short;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

function dispatchRedirect(int $count): array
{
    $short = Short::factory()->create();
    $times = [];
    $statuses = [];

    for ($i = 0; $i < $count; $i++) {
        $request = Request::create(
            route('shorts.redirect', $short->short_code),
            'GET',
        );

        $start = microtime(true);
        $response = app()->handle($request);
        $elapsed = (microtime(true) - $start) * 1000;

        $times[] = $elapsed;
        $statuses[] = $response->getStatusCode();
    }

    sort($times);

    return [
        'count' => $count,
        'times' => $times,
        'statuses' => $statuses,
        'total_ms' => round(array_sum($times), 2),
        'avg_ms' => round(array_sum($times) / $count, 2),
        'min_ms' => round(min($times), 2),
        'max_ms' => round(max($times), 2),
        'p95_ms' => round($times[(int) ($count * 0.95)] ?? 0, 2),
        'p99_ms' => round($times[(int) ($count * 0.99)] ?? 0, 2),
        'rps' => round(1000 / (array_sum($times) / $count), 1),
        'status_groups' => array_count_values($statuses),
    ];
}

function printScenario(string $label, array $r): void
{
    dump("  {$label}: {$r['rps']} RPS | Avg {$r['avg_ms']}ms | P95 {$r['p95_ms']}ms | P99 {$r['p99_ms']}ms");
}

it('projects capacity for 10M users across different time windows', function () {
    RateLimiter::for('redirect', fn () => Limit::none());

    $r = dispatchRedirect(2000);
    $rps = $r['rps'];

    dump("=== Capacity Projection (based on {$rps} RPS) ===");
    dump('');

    $scenarios = [
        '10M / 30 dias (avg)' => 10_000_000 / (30 * 24 * 3600),
        '10M / 7 dias (avg)' => 10_000_000 / (7 * 24 * 3600),
        '10M / 1 dia' => 10_000_000 / (24 * 3600),
        '10M / 1 hora' => 10_000_000 / 3600,
        '10M / 10 min' => 10_000_000 / 600,
        '10M / 1 min' => 10_000_000 / 60,
    ];

    foreach ($scenarios as $label => $requiredRps) {
        $canHandle = $requiredRps <= $rps;
        $utilization = round(($requiredRps / $rps) * 100, 1);
        $status = $canHandle ? 'OK' : 'NEED SCALING';

        dump("  {$label}:");
        dump('    Required: '.number_format($requiredRps, 1)." RPS | Utilization: {$utilization}% | {$status}");
    }

    dump('');
    dump('=== Single Server Capacity ===');
    dump("  Sequential RPS: {$rps}");
    dump('  Estimated concurrent (with queues): '.number_format($rps * 3).' RPS');
    dump('  Estimated concurrent (Octane/Swoole): '.number_format($rps * 10).' RPS');

    $this->assertTrue($rps > 0);
});

it('benchmarks cache lock overhead', function () {
    RateLimiter::for('redirect', fn () => Limit::none());

    $short = Short::factory()->create();
    $ipHash = hash('sha256', '127.0.0.1');
    $lockKey = "click_lock_bench:{$short->id}:{$ipHash}";

    $iterations = 5000;

    $start = microtime(true);
    for ($i = 0; $i < $iterations; $i++) {
        Cache::lock($lockKey, 5)->get();
        Cache::lock($lockKey, 5)->forceRelease();
    }
    $elapsed = (microtime(true) - $start) * 1000;

    dump('=== Cache Lock Benchmark ===');
    dump("  {$iterations} lock acquisitions: {$elapsed}ms");
    dump('  Avg per lock: '.round($elapsed / $iterations, 4).'ms');
    dump('  Lock RPS: '.number_format(round(1000 / ($elapsed / $iterations))));

    $this->assertLessThan(1000, $elapsed);
});
