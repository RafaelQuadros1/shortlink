<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider)
    {
        // Validate provider
        $validProviders = ['github', 'google'];
        if (! in_array($provider, $validProviders)) {
            Log::warning('Invalid OAuth provider attempted', ['provider' => $provider, 'ip' => request()->ip()]);
            return back()->with('error', 'Provider inválido.');
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback(string $provider)
    {
        // Validate provider
        $validProviders = ['github', 'google'];
        if (! in_array($provider, $validProviders)) {
            Log::warning('Invalid OAuth provider in callback', ['provider' => $provider, 'ip' => request()->ip()]);
            return redirect('/')->with('error', 'Provider inválido.');
        }

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            Log::error('OAuth authentication failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);
            return redirect('/')->with('error', 'Falha na autenticação. Tente novamente.');
        }

        // Validate social user data
        if (! $socialUser->getId() || ! $socialUser->getEmail()) {
            Log::warning('Incomplete OAuth user data', [
                'provider' => $provider,
                'ip' => request()->ip(),
            ]);
            return redirect('/')->with('error', 'Dados incompletos do provedor. Tente novamente.');
        }

        $user = User::where('social_provider', $provider)
            ->where('social_id', $socialUser->getId())
            ->first();

        if (! $user) {
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'social_id' => $socialUser->getId(),
                    'social_provider' => $provider,
                ]);
                Log::info('User linked to social provider', [
                    'user_id' => $user->id,
                    'provider' => $provider,
                ]);
            } else {
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? $socialUser->getEmail(),
                    'email' => $socialUser->getEmail(),
                    'social_id' => $socialUser->getId(),
                    'social_provider' => $provider,
                    'avatar' => $socialUser->getAvatar(),
                ]);
                Log::info('New user created via social auth', [
                    'user_id' => $user->id,
                    'provider' => $provider,
                ]);
            }
        }

        Auth::login($user, remember: false);
        Log::info('User authenticated via social provider', [
            'user_id' => $user->id,
            'provider' => $provider,
            'ip' => request()->ip(),
        ]);

        return redirect()->intended('/');
    }
}
