<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function apiKeys()
    {
        $apiKeys = auth()->user()
            ->apiKeys()
            ->latest()
            ->get();

        return view('settings.api-keys', compact('apiKeys'));
    }

    public function storeApiKey(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $key = ApiKey::generateKey();

        auth()->user()->apiKeys()->create([
            'name' => $validated['name'],
            'key' => $key['hashed'],
        ]);

        Log::channel('security')->info('API key created', [
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
        ]);

        return redirect()->route('settings.api-keys')
            ->with('plain_key', $key['plain'])
            ->with('success', 'API key criada com sucesso.');
    }

    public function destroyApiKey(ApiKey $apiKey)
    {
        if ($apiKey->user_id !== auth()->id()) {
            abort(403);
        }

        $apiKey->delete();

        Log::channel('security')->info('API key revoked', [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
        ]);

        return redirect()->route('settings.api-keys')
            ->with('success', 'API key revogada.');
    }
}
