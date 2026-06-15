<?php

namespace App\Http\Controllers;

use App\Actions\HandleSocialCallbackAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider, HandleSocialCallbackAction $action): RedirectResponse
    {
        if (! $action->isValidProvider($provider)) {
            Log::warning('Invalid OAuth provider attempted', ['provider' => $provider, 'ip' => request()->ip()]);

            return back()->with('error', 'Provider inválido.');
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback(string $provider, HandleSocialCallbackAction $action): RedirectResponse
    {
        if (! $action->isValidProvider($provider)) {
            Log::warning('Invalid OAuth provider in callback', ['provider' => $provider, 'ip' => request()->ip()]);

            return redirect('/')->with('error', 'Provider inválido.');
        }

        try {
            $action->execute($provider);
        } catch (\Exception $e) {
            Log::error('OAuth authentication failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);

            return redirect('/')->with('error', 'Falha na autenticação. Tente novamente.');
        }

        return redirect()->intended('/');
    }
}
