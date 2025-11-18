<?php

declare(strict_types=1);

use App\Modules\Space\Events\SpaceCreated;
use App\Modules\Space\Models\Space;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

function prepareSpaceUpdateTests(string $nameBefore): array
{
    $user = User::factory()->create();
    Space::factory()->for($user)->create();
    $spaceTwo = Space::factory()->for($user)->create(['name' => $nameBefore, 'slug' => Str::slug($nameBefore)]);

    return [$user, $spaceTwo];
}

it("has 'api', auth and 'verified' middleware applied", function () {
    $expectedMiddleware = ['api', 'auth:sanctum', 'verified'];
    $this->assertRouteHasMiddleware('spaces.index', $expectedMiddleware);
    $this->assertRouteHasMiddleware('spaces.update', $expectedMiddleware);
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

it("updates a user's Space", function () {
    // Arrange
    $nameBefore = 'Name before';
    [$user, $spaceTwo] = prepareSpaceUpdateTests($nameBefore);
    $positionBefore = $spaceTwo->position;

    $payload = ['position' => 1, 'name' => 'Updated name'];

    // Act
    $response = $this->actingAs($user, 'sanctum')->patchJson(
        route('spaces.update', ['space_slug' => 'name-before']),
        $payload
    );

    // Assert
    $responseOriginalContent = $response->getOriginalContent();
    $response->assertOk();

    expect($responseOriginalContent->id)->toBe($spaceTwo->id)
        ->and($responseOriginalContent->position)->not->toBe($positionBefore)
        ->and($responseOriginalContent->position)->toBe($payload['position'])
        ->and($responseOriginalContent->name)->toBe($payload['name']);
});

it('only updates relevant properties', function (array $requestData) {
    // Arrange
    $nameBefore = 'Name before';
    [$user, $spaceTwo] = prepareSpaceUpdateTests($nameBefore);
    $positionBefore = $spaceTwo->position;

    // Act
    $response = $this->actingAs($user, 'sanctum')->patchJson(
        route('spaces.update', ['space_slug' => 'name-before']),
        $requestData
    );

    // Assert
    nameAndPositionUpdateChecks($requestData, $response, ['name' => $nameBefore, 'position' => $positionBefore]);
})->with([
    ['requestData' => ['position' => 1]],
    ['requestData' => ['name' => 'Updated name']],
    ['requestData' => ['name' => 'Updated name', 'position' => 1]],
]);
