<?php

use App\Http\Controllers\Api\V1\ShortController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api_key', 'throttle:api'])->prefix('v1')->group(function () {
    Route::get('shorts', [ShortController::class, 'index']);
    Route::post('shorts', [ShortController::class, 'store']);
});
