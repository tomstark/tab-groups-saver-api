<?php

declare(strict_types=1);

use App\Modules\User\Domain\Models\User;
use App\Modules\User\Presentation\HTTP\Middleware\DenyIfUserEmailNotVerified;
use Illuminate\Support\Facades\Route;

it('allows verified users', function () {
    $verifiedUser = User::factory()->create();

    Route::get('/test-middleware', fn () => response()->json(['ok' => true]))
        ->middleware(DenyIfUserEmailNotVerified::class);

    // Act
    $response = $this->actingAs($verifiedUser, 'sanctum')->getJson('/test-middleware');

    // Assert
    $response->assertOk();
});

it('disallows non-verified users', function () {
    // Arrange
    $unverifiedUser = User::factory()->unverified()->create();

    Route::get('/test-middleware', fn () => response()->json(['ok' => true]))
        ->middleware(DenyIfUserEmailNotVerified::class);

    // Act
    $response = $this->actingAs($unverifiedUser, 'sanctum')->getJson('/test-middleware');

    // Assert
    $response->assertForbidden();
});
