<?php

namespace App\Jobs;

use App\Models\Click;
use App\Models\Short;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecordClickJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 5;

    public function __construct(
        public int $shortId,
        public ?string $ipAddress,
        public ?string $userAgent,
    ) {}

    public function handle(): void
    {
        if (! Short::where('id', $this->shortId)->exists()) {
            return;
        }

        Click::create([
            'short_id' => $this->shortId,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'clicked_at' => now(),
        ]);
    }
}
