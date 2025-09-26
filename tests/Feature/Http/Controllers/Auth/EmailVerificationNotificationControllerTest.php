<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

it("has 'api' and auth middleware applied", function () {
    $this->assertRouteHasMiddleware('auth.send-email-verification', ['api', 'auth:sanctum']);
});

it("sends an 'email verification' email", function () {
    // Arrange
    $user = User::factory()->unverified()->create();

    Notification::fake();

    // Act
    $response = $this->actingAs($user, 'sanctum')->postJson(
        route('auth.send-email-verification')
    );

    // Assert
    Notification::assertSentTo($user, VerifyEmail::class);

    $response->assertOk();
});

it('does not send email if user is already verified', function () {
    // Arrange
    $user = User::factory()->create();

    Notification::fake();

    // Act
    $response = $this->actingAs($user, 'sanctum')->postJson(
        route('auth.send-email-verification')
    );

    // Assert
    $response->assertOk();
    Notification::assertNotSentTo($user, VerifyEmail::class);
});
