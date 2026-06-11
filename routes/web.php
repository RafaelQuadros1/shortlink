<?php

use App\Http\Controllers\ShortController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('{short_code}', [ShortController::class, 'redirect'])
    ->name('shorts.redirect');

Route::resource('shorts', ShortController::class);
