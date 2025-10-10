<?php

declare(strict_types=1);

use App\Modules\Space\Events\SpaceCreated;
use App\Modules\Space\Exceptions\DuplicateUserSpaceNameException;
use App\Modules\Space\Models\Space;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Event;

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

it('creates a space for an authenticated user', function () {
    // Arrange
    $user = User::factory()->create();
    $payload = ['name' => 'My New Space'];

    // Act
    $response = $this->actingAs($user, 'sanctum')->postJson(route('spaces.create'), $payload);

    // Assert
    $allSpaces = Space::all();
    $firstSpace = $allSpaces->first();

    $response->assertCreated();
    expect($allSpaces)->toHaveCount(1)
        ->and($response->getOriginalContent())
        ->id->toBe($firstSpace->id)
        ->name->toBe($firstSpace->name)->toBe($payload['name']);
});

it("won't create a space if the name is a duplicate for the user", function () {
    // Arrange
    $this->withoutExceptionHandling();

    $user = User::factory()->create();
    $payload = ['name' => 'My New Space'];
    Space::factory()->for($user)->create($payload);

    // Act
    $this->actingAs($user, 'sanctum')->postJson(route('spaces.create'), $payload);

    // Assert
})->throws(DuplicateUserSpaceNameException::class);

it('dispatches a SpaceCreated event upon creating a new space', function () {
    // Arrange
    Event::fake();

    // Act
    $this->actingAs(User::factory()->create(), 'sanctum')->postJson(route('spaces.create'), ['name' => 'My New Space']);

    // Assert
    Event::assertDispatched(SpaceCreated::class);
});
