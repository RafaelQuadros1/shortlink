<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback(string $provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

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
            } else {
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    'social_id' => $socialUser->getId(),
                    'social_provider' => $provider,
                    'avatar' => $socialUser->getAvatar(),
                ]);
            }
        }

        Auth::login($user, remember: true);

        return redirect()->intended('/');
    }
}
