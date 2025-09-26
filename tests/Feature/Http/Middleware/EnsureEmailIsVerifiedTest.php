<?php

declare(strict_types=1);

use App\Http\Middleware\EnsureEmailIsVerified;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Testing\TestResponse;

it('allows verified users', function () {
    // Arrange
    $verifiedUser = User::factory()->create();
    $request = Request::create('/dummy')->setUserResolver(fn () => $verifiedUser);
    $middleware = new EnsureEmailIsVerified();

    // Act
    $response = new TestResponse($middleware->handle($request, fn ($req) => Response::json(['next' => true])));

    // Assert
    $response->assertOk();
});

it('disallows non-verified users', function () {
    // Arrange
    $unverifiedUser = User::factory()->unverified()->create();
    $request = Request::create('/dummy')->setUserResolver(fn () => $unverifiedUser);
    $middleware = new EnsureEmailIsVerified();

    // Act
    $response = new TestResponse($middleware->handle($request, fn ($req) => Response::json(['next' => true])));

    // Assert
    $response->assertForbidden();
});
