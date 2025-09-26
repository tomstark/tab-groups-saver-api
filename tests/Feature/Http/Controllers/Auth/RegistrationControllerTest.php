<?php

declare(strict_types=1);

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

it("has 'api' and 'guest' middleware applied", function () {
    $this->assertRouteHasMiddleware('auth.register', ['api', 'guest']);
});

test('new users can register', function () {
    // Arrange & Act
    $response = $this->postJson(route('auth.register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    // Assert
    $response->assertCreated();
});

it('dispatches a Registered event upon success', function () {
    // Arrange
    Event::fake();

    // Act
    $response = $this->postJson(route('auth.register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    // Assert
    $response->assertCreated();
    Event::assertDispatched(Registered::class);
});

test('mismatched post data fails without events fired', function () {
    // Arrange
    Event::fake();

    // Act
    $response = $this->postJson(route('auth.register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'passwordx',
    ]);

    // Assert
    $response->assertUnprocessable();
    Event::assertNotDispatched(Registered::class);
});
