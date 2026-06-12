<?php

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

    Route::resource('shorts', ShortController::class)
        ->only(['store'])
        ->middleware('throttle:30,1');

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

    // Rate limit redirect endpoint to prevent abuse
    Route::get('{short_code}', [ShortController::class, 'redirect'])
        ->name('shorts.redirect')
        ->middleware('throttle:300,1');

    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->name('social.redirect')
        ->middleware('throttle:30,1');

    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->name('social.callback')
        ->middleware('throttle:30,1');
});
