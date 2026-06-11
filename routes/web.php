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
    ->except(['edit', 'update'])
    ->only(['index', 'create', 'show', 'destroy'])
    ->middleware('auth');

Route::resource('shorts', ShortController::class)
    ->only(['store']);

Route::get('{short_code}', [ShortController::class, 'redirect'])
    ->name('shorts.redirect');

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
    ->name('social.redirect');

Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
    ->name('social.callback');
