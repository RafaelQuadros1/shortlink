<?php

namespace App\Providers;

use App\Models\Short;
use App\Observers\ShortObserver;
use App\Policies\ShortPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Short::observe(ShortObserver::class);

        Gate::policy(Short::class, ShortPolicy::class);

        RateLimiter::for('redirect', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip() ?? $request->userAgent());
        });

        // rate limit for login attempts (social auth)
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email.($request->ip() ?? $request->userAgent()));
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('short-store', function (Request $request) {
            if ($request->user()) {
                return Limit::perMinute(30)->by($request->user()->id);
            }

            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
