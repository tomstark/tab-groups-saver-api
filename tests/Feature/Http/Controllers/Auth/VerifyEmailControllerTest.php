<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

it("a has 'api' and 'signed' middleware applied", function () {
    $this->assertRouteHasMiddleware('auth.mark-email-verified', ['api', 'signed']);
});

test('email can be verified', function () {
    // Arrange
    $user = User::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'auth.mark-email-verified',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    // Act
    $response = $this->actingAs($user, 'sanctum')->get($verificationUrl);

    // Assert
    Event::assertDispatched(Verified::class);
    $response->assertOk();
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

test('email is not verified with invalid hash', function () {
    // Arrange
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'auth.mark-email-verified',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    // Act
    $response = $this->actingAs($user)->get($verificationUrl);

    // Assert
    $response->assertForbidden();
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('attempting to re-verify returns ok without dispatching verified event', function () {
    // Arrange
    $verifiedUser = User::factory()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'auth.mark-email-verified',
        now()->addMinutes(60),
        ['id' => $verifiedUser->id, 'hash' => sha1($verifiedUser->email)]
    );

    // Act
    $response = $this->actingAs($verifiedUser)->get($verificationUrl);

    // Assert
    Event::assertNotDispatched(Verified::class);
    $response->assertOk();
    expect($verifiedUser->fresh()->hasVerifiedEmail())->toBeTrue();
});
