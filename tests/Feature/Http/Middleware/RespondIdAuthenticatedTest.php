<?php

declare(strict_types=1);

use App\Http\Middleware\RespondIfAuthenticated;
use App\Models\User;
use Illuminate\Support\Facades\Route;

it('allows guest users', function () {
    // Arrange
    Route::get('/test-middleware', fn () => response()->json(['ok' => true]))
        ->middleware(RespondIfAuthenticated::class);

    // Act
    $response = $this->getJson('/test-middleware');

    // Assert
    $response->assertOk();
});

it('disallows authenticated users', function () {
    // Arrange
    Route::get('/test-middleware', fn () => response()->json(['ok' => true]))
        ->middleware(RespondIfAuthenticated::class);

    // Act
    $response = $this->actingAs(User::factory()->create(), 'sanctum')->getJson('/test-middleware');

    // Assert
    $response->assertForbidden();
});
