<?php

declare(strict_types=1);

use App\Modules\User\Actions\Facades\CreateEmailVerificationSignedRoute;
use App\Modules\User\HTTP\Enums\AuthRouteNames;
use App\Modules\User\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;

it("has 'api' and 'signed' middleware applied", function () {
    $this->assertRouteHasMiddleware(AuthRouteNames::MarkEmailVerified->value, ['api', 'signed:relative']);
});

test('email can be verified', function () {
    // Arrange
    Event::fake();
    $user = User::factory()->unverified()->create();

    // Act
    $response = $this->actingAs($user, 'sanctum')->get(
        CreateEmailVerificationSignedRoute::run($user)
    );

    // Assert
    Event::assertDispatched(Verified::class);
    $response->assertOk();
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

test('email is not verified with invalid hash', function () {
    // Arrange
    Event::fake();
    $user = User::factory()->unverified()->create();

    // Act
    $response = $this->actingAs($user)->get(
        CreateEmailVerificationSignedRoute::run($user, ['id' => $user->id, 'hash' => sha1('wrong-email')])
    );

    // Assert
    Event::assertNotDispatched(Verified::class);
    $response->assertForbidden();
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('attempting to re-verify returns ok without dispatching verified event', function () {
    // Arrange
    Event::fake();
    $verifiedUser = User::factory()->create();

    // Act
    $response = $this->actingAs($verifiedUser)->get(
        CreateEmailVerificationSignedRoute::run($verifiedUser)
    );

    // Assert
    Event::assertNotDispatched(Verified::class);
    $response->assertOk();
    expect($verifiedUser->fresh()->hasVerifiedEmail())->toBeTrue();
});
