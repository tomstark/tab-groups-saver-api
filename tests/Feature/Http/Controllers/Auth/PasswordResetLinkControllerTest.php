<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

it("has 'api' and auth middleware applied", function () {
    $this->assertRouteHasMiddleware('auth.send-forgotten-password-link', ['api', 'auth:sanctum']);
});

test('reset password link can be requested', function () {
    // Arrange
    Notification::fake();

    $this->user = User::factory()->create();

    // Act
    $this
        ->actingAs($this->user, 'sanctum')
        ->postJson(route('auth.send-forgotten-password-link'), ['email' => $this->user->email]);

    // Assert
    Notification::assertSentTo($this->user, ResetPassword::class);
});

it('fails with unknown email', function () {
    // Arrange
    $this->withoutExceptionHandling();

    Notification::fake();

    $this->user = User::factory()->create();

    // Act
    $this
        ->actingAs($this->user, 'sanctum')
        ->postJson(route('auth.send-forgotten-password-link'), ['email' => 'not_in_system@example.com']);

    // Assert
    Notification::assertNotSentTo($this->user, ResetPassword::class);
})->throws(ValidationException::class);
