<?php

declare(strict_types=1);

use App\Modules\Space\Models\Space;
use App\Modules\User\Models\User;

it("has 'api', auth and 'verified' middleware applied", function () {
    $this->assertRouteHasMiddleware('spaces.index', ['api', 'auth:sanctum', 'verified']);
});

test("index route returns only the logged in user's spaces", function () {
    // Arrange
    Space::factory()->for(User::factory()->create())->create(); // another user's data
    $user = User::factory()->create();
    $userSpaceCount = 5;
    $userSpaces = Space::factory()->for($user)->count($userSpaceCount)->create();

    // Act
    $response = $this->actingAs($user, 'sanctum')->getJson(route('spaces.index'));

    // Assert
    $responseOriginalContent = $response->getOriginalContent();
    $response->assertOk();
    expect(Space::all())->toHaveCount(6)
        ->and($responseOriginalContent)->toHaveCount($userSpaceCount)
        ->and($responseOriginalContent)->pluck('id')->toEqual($userSpaces->pluck('id'));
});
