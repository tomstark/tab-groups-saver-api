<?php

declare(strict_types=1);

use App\Modules\User\HTTP\Controllers\Auth\AuthenticationController;
use App\Modules\User\HTTP\Controllers\Auth\EmailVerificationNotificationController;
use App\Modules\User\HTTP\Controllers\Auth\NewPasswordController;
use App\Modules\User\HTTP\Controllers\Auth\PasswordResetLinkController;
use App\Modules\User\HTTP\Controllers\Auth\RegistrationController;
use App\Modules\User\HTTP\Controllers\Auth\VerifyEmailController;
use App\Modules\User\HTTP\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ⭐️ DON'T FORGET ⭐️ All are prefixed with '/api'

$throttleMiddleware = ['throttle:20,1'];

$signedMiddleware = [...$throttleMiddleware, 'signed:relative'];
$guestMiddleware = [...$throttleMiddleware, 'guest'];
$authSlimMiddleware = [...$throttleMiddleware, 'auth:sanctum'];

$fullAuthMiddleware = [...$authSlimMiddleware, 'verified'];

Route::middleware($fullAuthMiddleware)->group(function () {
    Route::get('/user', [UserController::class, 'show'])->name('users.show');
});

// ToDo - just testing, remove when ready
Route::middleware($fullAuthMiddleware)->group(function () {
    Route::get('/sanctum-test', [AuthenticationController::class, 'sanctumTest'])->name('auth.temp-sanctum-test');
});

Route::name('auth.')->group(
    function () use ($signedMiddleware, $guestMiddleware, $authSlimMiddleware) {
        // ⭐️ All route names hereafter are prefixed with 'auth.' ⭐ (i.e. below 'login' route is actually 'auth.login')

        Route::middleware($guestMiddleware)->group(function () {
            Route::post('/register', [RegistrationController::class, 'store'])->name('register');
            Route::post('/login', [AuthenticationController::class, 'store'])->name('login');
        });

        Route::middleware($authSlimMiddleware)->group(function () {
            Route::post('/email/verification-notification', EmailVerificationNotificationController::class)
                ->name('send-email-verification');

            Route::post('/forgot-password', PasswordResetLinkController::class)->name('send-forgotten-password-link');
            Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('reset-password');

            Route::post('/logout', [AuthenticationController::class, 'destroy'])->name('logout');
        });

        Route::middleware($signedMiddleware)->group(function () {
            Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->name('mark-email-verified');
        });
    }
);
