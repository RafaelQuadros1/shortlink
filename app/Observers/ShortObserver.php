<?php

namespace App\Observers;

use App\Models\Short;
use App\Services\Encode;
use Illuminate\Support\Facades\Cache;

class ShortObserver
{
    public function created(Short $short): void
    {
        $updates = [];

        if (is_null($short->short_code)) {
            $updates['short_code'] = (new Encode)->code($short->id);
        }

        if (is_null($short->user_id) && is_null($short->expires_at)) {
            $updates['expires_at'] = now()->addDays(30);
        }

        if ($updates !== []) {
            $short->updateQuietly($updates);
        }
    }

    public function updated(Short $short): void
    {
        Cache::forget("short:{$short->id}");
    }

    public function deleted(Short $short): void
    {
        Cache::forget("short:{$short->id}");
    }
}
