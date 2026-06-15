<?php

namespace App\Actions;

use App\Models\ApiKey;
use Illuminate\Support\Facades\Log;

class RevokeApiKeyAction
{
    public function execute(ApiKey $apiKey): void
    {
        Log::channel('security')->info('API key revoked', [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
        ]);

        $apiKey->delete();
    }
}
