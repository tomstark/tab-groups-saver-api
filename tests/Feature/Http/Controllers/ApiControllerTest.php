<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\postJson;

it('authenticates a user with valid credentials', function () {
    $password = 'secret123';

    User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make($password),
    ]);

    $response = postJson(
        route('api-login'),
        ['email' => 'john@example.com', 'password' => $password]
    );

    $response
        ->assertOk()
        ->assertJsonStructure(['message', 'data'])
        ->assertJson(['message' => 'Successfully logged in']);

    expect(Auth::check())->toBeTrue();
});

it('fails to authenticate with invalid credentials', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('correct-password'),
    ]);

    $response = postJson(
        route('api-login'),
        ['email' => 'john@example.com', 'password' => 'wrong-password']
    );

    $response
        ->assertStatus(401)
        ->assertJson(['message' => 'Failed to logged in']);
});

it('validates required email and password fields', function () {
    $response = postJson(route('api-login'), []);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

// ToDo - temp-sanctum-test based tests are temporary, evolve / remove later
it('Sanctum test ', function () {
    $response = $this->withoutMiddleware()->getJson(route('temp-sanctum-test'));

    $response->assertOk();
});

it('Sanctum test returns 401 without token', function () {
    $response = $this->getJson(route('temp-sanctum-test'));

    $response->assertUnauthorized();
});
