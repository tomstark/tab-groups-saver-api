<?php

declare(strict_types=1);

use App\Modules\User\Domain\Models\User;
use App\Modules\User\Presentation\HTTP\Enums\AuthRouteNames;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    $this->routeName = AuthRouteNames::SendForgottenPasswordLink->value;
});

it("has 'api' and auth middleware applied", function () {
    $this->assertRouteHasMiddleware($this->routeName, ['api', 'auth:sanctum']);
});

test('reset password link can be requested', function () {
    // Arrange
    Notification::fake();
    $this->user = User::factory()->create();

    // Act
    $this
        ->actingAs($this->user, 'sanctum')
        ->postJson(route($this->routeName), ['email' => $this->user->email]);

    // Assert
    Notification::assertSentTo($this->user, ResetPassword::class);
});

it('fails with unknown email', function () {
    // Arrange
    Notification::fake();
    $this->withoutExceptionHandling();
    $this->user = User::factory()->create();

    // Act
    $this
        ->actingAs($this->user, 'sanctum')
        ->postJson(route($this->routeName), ['email' => 'not_in_system@example.com']);

    // Assert
    Notification::assertNotSentTo($this->user, ResetPassword::class);
})->throws(ValidationException::class);
