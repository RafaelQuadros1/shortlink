<?php

use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShortController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('security.headers')->group(function () {
    Route::get('/', function () {
        return view('app');
    })->name('home');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');

    Route::resource('shorts', ShortController::class)
        ->only(['index', 'create', 'show', 'edit', 'update', 'destroy'])
        ->middleware('auth');

    Route::post('/shorts', [ShortController::class, 'store'])
        ->name('shorts.store')
        ->middleware('throttle:short-store');

    Route::get('/link-nao-encontrado', function () {
        return view('shorts.not-found');
    })->name('shorts.not-found');

    Route::get('/politica-de-privacidade', function () {
        return view('legal.privacy');
    })->name('legal.privacy');

    Route::get('/politica-de-cookies', function () {
        return view('legal.cookies');
    })->name('legal.cookies');

    Route::get('/termos-de-uso', function () {
        return view('legal.terms');
    })->name('legal.terms');

    Route::get('/api/exemplo', function () {
        return view('api.example');
    })->name('api.example');

    Route::middleware('auth')->prefix('configuracoes')->name('settings.')->group(function () {
        Route::get('/api-keys', [SettingsController::class, 'apiKeys'])->name('api-keys');
        Route::post('/api-keys', [SettingsController::class, 'storeApiKey'])->name('api-keys.store');
        Route::delete('/api-keys/{apiKey}', [SettingsController::class, 'destroyApiKey'])->name('api-keys.destroy');
    });

    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->name('social.redirect')
        ->middleware('throttle:30,1');

    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->name('social.callback')
        ->middleware('throttle:30,1');
});

Route::get('{short_code}', [ShortController::class, 'redirect'])
    ->name('shorts.redirect')
    ->middleware('throttle:300,1');
