<?php

use App\Http\Controllers\ShortController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    ->only(['store']);

Route::get('/link-nao-encontrado', function () {
    return view('shorts.not-found');
})->name('shorts.not-found');

Route::get('{short_code}', [ShortController::class, 'redirect'])
    ->middleware('throttle:redirect')
    ->name('shorts.redirect');

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
    ->middleware('throttle:login')
    ->whereIn('provider', ['google'])
    ->name('social.redirect');

Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
    ->middleware('throttle:login')
    ->whereIn('provider', ['google'])
    ->name('social.callback');
