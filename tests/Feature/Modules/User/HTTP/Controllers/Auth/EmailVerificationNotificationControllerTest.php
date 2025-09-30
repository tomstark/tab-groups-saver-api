<?php

declare(strict_types=1);

use App\Modules\User\HTTP\Enums\AuthRouteNames;
use App\Modules\User\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->routeName = AuthRouteNames::SendEmailVerification->value;
});

it("has 'api' and auth middleware applied", function () {
    $this->assertRouteHasMiddleware($this->routeName, ['api', 'auth:sanctum']);
});

it("sends an 'email verification' email", function () {
    // Arrange
    Notification::fake();
    $user = User::factory()->unverified()->create();

    // Act
    $response = $this->actingAs($user, 'sanctum')->postJson(route($this->routeName));

    // Assert
    Notification::assertSentTo($user, VerifyEmail::class);

    $response->assertOk();
});

it('does not send email if user is already verified', function () {
    // Arrange
    Notification::fake();
    $user = User::factory()->create();

    // Act
    $response = $this->actingAs($user, 'sanctum')->postJson(route($this->routeName));

    // Assert
    $response->assertOk();
    Notification::assertNotSentTo($user, VerifyEmail::class);
});
