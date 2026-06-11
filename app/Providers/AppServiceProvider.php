<?php

namespace App\Providers;

use App\Models\Short;
use App\Observers\ShortObserver;
use App\Policies\ShortPolicy;
use Illuminate\Support\Facades\Gate;
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
    }
}
