<?php

declare(strict_types=1);

use App\Modules\Space\Events\SpaceCreated;
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

it('disallows duplicate user spaces', function () {
    // Arrange
    $existingName = 'This is a space';
    $user = User::factory()->create();
    Space::factory()->for($user)->create(['name' => $existingName]);
    Event::fake();

    // Act
    $response = $this->actingAs($user, 'sanctum')->postJson(route('spaces.create'), ['name' => $existingName]);

    // Assert
    $response->assertUnprocessable();
    Event::assertNotDispatched(SpaceCreated::class);
});
