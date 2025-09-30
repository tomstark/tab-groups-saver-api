<?php

declare(strict_types=1);

use App\Modules\User\Domain\Models\User;

it("has 'api', auth and 'verified' middleware applied", function () {
    $this->assertRouteHasMiddleware('users.show', ['api', 'auth:sanctum', 'verified']);
});

it('show route returns the auth user', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = $this->actingAs($user, 'sanctum')->getJson(route('users.show'));

    // Assert
    $response->assertOk();
    $this->assertTrue($response->getOriginalContent()->is($user));
});
