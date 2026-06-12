<?php

namespace App\Console\Commands;

use App\Models\Short;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('shorts:cleanup')]
#[Description('Delete all expired short links from the database')]
class CleanupExpiredShorts extends Command
{
    public function handle(): int
    {
        $deleted = Short::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->delete();

        $this->info("Deleted {$deleted} expired short link(s).");

        return self::SUCCESS;
    }
}
