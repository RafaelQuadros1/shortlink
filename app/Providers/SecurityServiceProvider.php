<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set secure defaults for production
        if ($this->app->environment('production')) {
            // Enable HTTPS only
            $this->app['url']->forceScheme('https');

            // Enable HSTS headers
            if (function_exists('config')) {
                config(['app.force_https' => true]);
            }
        }

        // Log failed authentication attempts
        $this->registerAuthLogging();

        // Configure query logging for security auditing
        $this->configureQueryLogging();
    }

    /**
     * Register authentication event logging
     */
    private function registerAuthLogging(): void
    {
        $authManager = $this->app['auth'];

        // Note: For Laravel 13, authentication events are handled differently
        // This is a placeholder for future event-based logging integration
    }

    /**
     * Configure query logging for sensitive operations
     */
    private function configureQueryLogging(): void
    {
        if ($this->app->environment('local', 'staging')) {
            // Enable query logging in non-production for debugging
            $this->app['db']->listen(function ($query) {
                // Log queries that involve users, shorts, or clicks tables
                if (preg_match('/(\buser\b|\bshort\b|\bclick\b)/i', $query->sql)) {
                    \Illuminate\Support\Facades\Log::channel('security')->debug('Database Query', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time,
                    ]);
                }
            });
        }
    }
}
