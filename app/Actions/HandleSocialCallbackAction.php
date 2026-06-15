<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class HandleSocialCallbackAction
{
    private const VALID_PROVIDERS = ['github', 'google'];

    public function execute(string $provider): void
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = $this->findOrCreateUser($socialUser, $provider);

        Auth::login($user, remember: false);

        Log::info('User authenticated via social provider', [
            'user_id' => $user->id,
            'provider' => $provider,
            'ip' => request()->ip(),
        ]);
    }

    public function isValidProvider(string $provider): bool
    {
        return in_array($provider, self::VALID_PROVIDERS);
    }

    private function findOrCreateUser(SocialiteUser $socialUser, string $provider): User
    {
        $user = User::where('social_provider', $provider)
            ->where('social_id', $socialUser->getId())
            ->first();

        if ($user) {
            return $user;
        }

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

            return $user;
        }

        return $this->createUser($socialUser, $provider);
    }

    private function createUser(SocialiteUser $socialUser, string $provider): User
    {
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

        return $user;
    }
}
