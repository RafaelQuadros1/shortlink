<?php

namespace App\Observers;

use App\Models\Short;
use App\Services\Encode;
use Illuminate\Support\Facades\Cache;

class ShortObserver
{
    public function created(Short $short): void
    {
        if (is_null($short->short_code)) {
            $short->updateQuietly([
                'short_code' => (new Encode)->code($short->id),
            ]);
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
