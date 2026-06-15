<?php

namespace App\Http\Controllers;

use App\Actions\RevokeApiKeyAction;
use App\Models\ApiKey;
use Illuminate\Http\Request;

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

        return redirect()->route('settings.api-keys')
            ->with('plain_key', $key['plain'])
            ->with('success', 'API key criada com sucesso.');
    }

    public function destroyApiKey(ApiKey $apiKey, RevokeApiKeyAction $action)
    {
        $this->authorize('delete', $apiKey);

        $action->execute($apiKey);

        return redirect()->route('settings.api-keys')
            ->with('success', 'API key revogada.');
    }
}
