<?php

declare(strict_types=1);

use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

test("route 'auth.temp-sanctum-test' has 'api', auth and 'verified' middleware applied", function () {
    $this->assertRouteHasMiddleware('auth.temp-sanctum-test', ['api', 'auth:sanctum', 'verified']);
});

test("route 'auth.login' has 'api' and 'guest' middleware applied", function () {
    $this->assertRouteHasMiddleware('auth.login', ['api', 'guest']);
});

test("route 'auth.logout' has 'api' and auth middleware applied", function () {
    $this->assertRouteHasMiddleware('auth.logout', ['api', 'auth:sanctum']);
});

it('authenticates a user with valid credentials', function () {
    // Arrange
    $password = 'secret123';

    User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make($password),
    ]);

    // Act
    $response = $this->postJson(
        route('auth.login'),
        ['email' => 'john@example.com', 'password' => $password]
    );

    // Assert
    $response
        ->assertOk()
        ->assertJsonStructure(['message', 'token'])
        ->assertJson(['message' => 'Successfully logged in']);

    expect(Auth::check())->toBeTrue();
});

it('fails to authenticate with invalid credentials', function () {
    // Arrange
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('correct-password'),
    ]);

    // Act
    $response = $this->postJson(
        route('auth.login'),
        ['email' => 'john@example.com', 'password' => 'wrong-password']
    );

    // Assert
    $response
        ->assertUnauthorized()
        ->assertJson(['message' => 'Failed to log in']);
});

it('validates required email and password fields', function () {
    // Arrange & Act
    $response = $this->postJson(route('auth.login'), []);

    // Assert
    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);
});

test('users can logout', function () {
    // Arrange
    $tokenMock = Mockery::mock(new PersonalAccessToken);
    $user = (User::factory()->create())->withAccessToken($tokenMock);

    // Act
    $response = $this->actingAs($user, 'sanctum')->postJson(route('auth.logout'));

    // Assert
    $response->assertOk();
    $tokenMock->shouldHaveReceived('delete')->once();
});

// ToDo - temporary, evolve / remove later
it('Sanctum test', function () {
    // Arrange & Act
    $response = $this->actingAs(User::factory()->create(), 'sanctum')->getJson(route('auth.temp-sanctum-test'));

    // Assert
    $response->assertOk();
});
