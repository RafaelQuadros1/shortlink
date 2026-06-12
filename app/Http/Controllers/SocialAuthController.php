<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\MissingStateException;
use Laravel\Socialite\Two\OAuthException;

class SocialAuthController extends Controller
{
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (InvalidStateException) {
            return redirect('/')->with('error', 'A autenticação expirou. Tente novamente.');
        } catch (MissingStateException) {
            return redirect('/')->with('error', 'A autenticação expirou. Tente novamente.');
        } catch (OAuthException $e) {
            Log::error('Socialite OAuth error', ['provider' => $provider, 'error' => $e->getMessage()]);

            return redirect('/')->with('error', 'Falha na autenticação com '.ucfirst($provider).'. Tente novamente.');
        } catch (\Exception $e) {
            Log::error('Socialite unexpected error', ['provider' => $provider, 'error' => $e->getMessage()]);

            return redirect('/')->with('error', 'Erro inesperado na autenticação. Tente novamente.');
        }

        $user = User::firstOrCreate(
            [
                'social_provider' => $provider,
                'social_id' => $socialUser->getId(),
            ],
            [
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'avatar' => $socialUser->getAvatar(),
            ]
        );

        Auth::login($user);

        return redirect()->intended('/');
    }
}
