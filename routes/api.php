<?php

declare(strict_types=1);

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// ⭐️ DON'T FORGET ⭐️ All are prefixed with '/api'

Route::post('/login', [LoginController::class, 'authenticate'])->name('api-login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/sanctum-test', [LoginController::class, 'sanctumTest']);
});
