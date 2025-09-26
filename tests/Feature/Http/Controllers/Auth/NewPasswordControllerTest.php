<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

it("has 'api' and auth middleware applied", function () {
    $this->assertRouteHasMiddleware('auth.reset-password', ['api', 'auth:sanctum']);
});

describe('Password reset functionality', function () {
    beforeEach(function () {
        // Arrange
        Notification::fake();

        $this->user = User::factory()->create();

        // Act
        $this
            ->actingAs($this->user, 'sanctum')
            ->postJson(route('auth.send-forgotten-password-link'), ['email' => $this->user->email]);
    });

    test('password can be reset with valid token', function () {
        // Assert
        Notification::assertSentTo($this->user, ResetPassword::class, function (object $notification) {
            $response = $this->postJson(route('auth.reset-password'), [
                'token' => $notification->token,
                'email' => $this->user->email,
                'password' => 'passwordx',
                'password_confirmation' => 'passwordx',
            ]);

            $response->assertOk();

            return true;
        });
    });

    test('password confirmation mismatch fails', function () {
        // Assert
        Notification::assertSentTo($this->user, ResetPassword::class, function (object $notification) {
            $response = $this->postJson(route('auth.reset-password'), [
                'token' => $notification->token,
                'email' => $this->user->email,
                'password' => 'passwordx',
                'password_confirmation' => 'passwordxxxxxxxxx',
            ]);

            $response->assertUnprocessable();

            return true;
        });
    });

    test('invalid token fails with exception thrown', function () {
        // Arrange (don't overlook above beforeEach also)
        $this->withoutExceptionHandling();

        // Assert
        Notification::assertSentTo($this->user, ResetPassword::class, function () {
            $response = $this->postJson(route('auth.reset-password'), [
                'token' => 'invalid_token',
                'email' => $this->user->email,
                'password' => 'passwordx',
                'password_confirmation' => 'passwordx',
            ]);

            $response->assertUnprocessable();

            return true;
        });
    })->throws(ValidationException::class);
});
