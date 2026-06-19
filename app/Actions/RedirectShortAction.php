<?php

namespace App\Actions;

use App\Jobs\RecordClickJob;
use App\Models\Short;
use App\Services\Decode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
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

        $this->recordClick($short);

        return redirect()->away($short->url_origin);
    }

    private function recordClick(Short $short): void
    {
        RecordClickJob::dispatch(
            shortId: $short->id,
            ipAddress: hash('sha256', request()->ip()),
            userAgent: request()->userAgent(),
        );
    }
}
