<?php

declare(strict_types=1);

use App\Modules\Space\HTTP\Controllers\SpaceController;
use App\Modules\Tab\HTTP\Controllers\TabController;
use App\Modules\User\HTTP\Controllers\Auth\AuthenticationController;
use App\Modules\User\HTTP\Controllers\Auth\EmailVerificationNotificationController;
use App\Modules\User\HTTP\Controllers\Auth\NewPasswordController;
use App\Modules\User\HTTP\Controllers\Auth\PasswordResetLinkController;
use App\Modules\User\HTTP\Controllers\Auth\RegistrationController;
use App\Modules\User\HTTP\Controllers\Auth\VerifyEmailController;
use App\Modules\User\HTTP\Controllers\UserController;
use App\Modules\Window\HTTP\Controllers\WindowController;
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

Route::name('auth.')->group(
    function () use ($signedMiddleware, $guestMiddleware, $authSlimMiddleware) {
        // All route names hereafter are prefixed with 'auth.' (i.e. below 'login' route is actually 'auth.login')

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

Route::name('spaces.')->group(
    function () use ($fullAuthMiddleware) {
        // All route names hereafter are prefixed with 'spaces.'

        Route::middleware($fullAuthMiddleware)->group(function () {
            Route::get('/spaces', [SpaceController::class, 'index'])->name('index');

            Route::post('/spaces', [SpaceController::class, 'store'])->name('create');

            // ToDo
            // Route::patch('/spaces/{space_id}', [SpaceController::class, 'update'])->name('update');
            // Route::delete('/spaces/{space_id}', [SpaceController::class, 'destroy'])->name('delete');
        });
    }
);

Route::name('windows.')->group(
    function () use ($fullAuthMiddleware) {
        // All route names hereafter are prefixed with 'windows.'

        Route::middleware($fullAuthMiddleware)->group(function () {
            Route::get('/spaces/{space_id}/windows', [WindowController::class, 'index'])
                ->name('index')
                ->whereUuid('space_id');

            // ToDo
            // Route::post('/windows', [WindowController::class, 'store'])->name('create');
            // Route::patch('/windows/{window_id}', [WindowController::class, 'update'])->name('update');
            // Route::delete('/windows/{window_id}', [WindowController::class, 'destroy'])->name('delete');
        });
    }
);

Route::name('tabs.')->group(
    function () use ($fullAuthMiddleware) {
        // All route names hereafter are prefixed with 'tabs.'

        Route::middleware($fullAuthMiddleware)->group(function () {
            Route::get('/tabs/{tab_id}', [TabController::class, 'show'])
                ->name('show')
                ->whereUuid('tab_id');

            // ToDo
            // Route::put('/tabs/{tab_id}', [TabController::class, 'update'])->name('update');
            // Route::delete('/tabs/{tab_id}', [TabController::class, 'destroy'])->name('delete');
        });
    }
);
