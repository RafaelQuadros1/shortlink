<?php

namespace App\Actions;

use App\Models\Click;
use App\Models\Short;
use App\Services\Decode;
use App\Services\InputValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RedirectShortAction
{
    public function execute(string $code): RedirectResponse
    {
        try {
            $id = (new Decode)->decode($code);
            $short = Short::findOrFail($id);
        } catch (ModelNotFoundException|\InvalidArgumentException) {
            Log::warning('Short link not found', [
                'code' => $code,
                'ip' => request()->ip(),
            ]);

            return redirect()->route('shorts.not-found');
        }

        if ($short->isExpired()) {
            Log::info('Short link expired', [
                'short_id' => $short->id,
                'code' => $code,
                'ip' => request()->ip(),
            ]);

            return redirect()->route('shorts.not-found');
        }

        if (! InputValidator::validateUrl($short->url_origin)) {
            Log::warning('Short link blocked at redirect — invalid URL', [
                'short_id' => $short->id,
                'ip' => request()->ip(),
            ]);

            return redirect()->route('shorts.not-found');
        }

        $this->recordClick($short);

        return redirect()->away($short->url_origin);
    }

    private function recordClick(Short $short): void
    {
        $ipHash = hash('sha256', request()->ip());
        $lockKey = "click_lock:{$short->id}:{$ipHash}";

        if (Cache::lock($lockKey, 5)->get()) {
            Click::create([
                'short_id' => $short->id,
                'ip_address' => $ipHash,
                'user_agent' => request()->userAgent(),
                'clicked_at' => now(),
            ]);
        }
    }
}
