<?php

namespace App\Observers;

use App\Models\Short;
use App\Services\Encode;

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
}
