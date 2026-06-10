<?php

use App\Http\Controllers\ShortController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});


Route::resource('shorts', ShortController::class);
