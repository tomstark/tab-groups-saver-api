<?php

declare(strict_types=1);

use App\Http\Controllers\ApiLoginController;
use Illuminate\Support\Facades\Route;

// ⭐️ DON'T FORGET ⭐️ All are prefixed with '/api'

Route::post('/login', [ApiLoginController::class, 'authenticate'])->name('api-login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/sanctum-test', [ApiLoginController::class, 'sanctumTest'])->name('temp-sanctum-test');
});
