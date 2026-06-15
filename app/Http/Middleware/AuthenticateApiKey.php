<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-Key');

        if (! $key || ! str_starts_with($key, 'sk_')) {
            return response()->json(['message' => 'API key not provided.'], Response::HTTP_UNAUTHORIZED);
        }

        $apiKey = $this->resolveApiKey($key);

        if (! $apiKey) {
            return response()->json(['message' => 'Invalid API key.'], Response::HTTP_UNAUTHORIZED);
        }

        if ($apiKey->isExpired()) {
            return response()->json(['message' => 'API key expired.'], Response::HTTP_UNAUTHORIZED);
        }

        $apiKey->update(['last_used_at' => now()]);

        Auth::setUser($apiKey->user);

        return $next($request);
    }

    private function resolveApiKey(string $key): ?ApiKey
    {
        $legacyHash = hash('sha256', $key);

        $candidate = ApiKey::where('key', $legacyHash)->first();
        if ($candidate) {
            return $candidate;
        }

        $candidates = ApiKey::where('key', 'like', '$%')->get();

        foreach ($candidates as $candidate) {
            if (Hash::check($key, $candidate->key)) {
                return $candidate;
            }
        }

        return null;
    }
}
