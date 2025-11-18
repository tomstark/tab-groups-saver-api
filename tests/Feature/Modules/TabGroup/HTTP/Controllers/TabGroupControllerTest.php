<?php

declare(strict_types=1);

use App\Modules\Space\Models\Space;
use App\Modules\TabGroup\Models\TabGroup;
use App\Modules\User\Models\User;
use App\Modules\Window\Models\Window;

function prepareTabGroupUpdateTests(string $nameBefore): array
{
    $user = User::factory()->create();
    $space = Space::factory()->for($user)->create();
    $window = Window::factory()->for($space)->create();
    TabGroup::factory()->for($window)->create();
    /** @var TabGroup $firstTabGroup */
    $tabGroupTwo = TabGroup::factory()->for($window)->create(['name' => $nameBefore]);

    return [$user, $tabGroupTwo];
}

it("has 'api', auth and 'verified' middleware applied", function () {
    $this->assertRouteHasMiddleware('tabGroups.update', ['api', 'auth:sanctum', 'verified']);
});

it("updates a user's TabGroup", function () {
    // Arrange
    $nameBefore = 'Name before';
    [$user, $tabGroupTwo] = prepareTabGroupUpdateTests($nameBefore);
    $positionBefore = $tabGroupTwo->position;

    $payload = ['position' => 1, 'name' => 'Updated name'];

    // Act
    $response = $this->actingAs($user, 'sanctum')->patchJson(
        route('tabGroups.update', ['tab_group_id' => $tabGroupTwo->id]),
        $payload
    );

    // Assert
    $responseOriginalContent = $response->getOriginalContent();
    $response->assertOk();

    expect($responseOriginalContent->id)->toBe($tabGroupTwo->id)
        ->and($responseOriginalContent->position)->not->toBe($positionBefore)
        ->and($responseOriginalContent->position)->toBe($payload['position'])
        ->and($responseOriginalContent->name)->toBe($payload['name']);
});

it('only updates relevant properties', function (array $requestData) {
    // Arrange
    $nameBefore = 'Name before';
    [$user, $tabGroupTwo] = prepareTabGroupUpdateTests($nameBefore);
    $positionBefore = $tabGroupTwo->position;

    // Act
    $response = $this->actingAs($user, 'sanctum')->patchJson(
        route('tabGroups.update', ['tab_group_id' => $tabGroupTwo->id]),
        $requestData
    );

    // Assert
    nameAndPositionUpdateChecks($requestData, $response, ['name' => $nameBefore, 'position' => $positionBefore]);
})->with([
    ['requestData' => ['position' => 1]],
    ['requestData' => ['name' => 'Updated name']],
    ['requestData' => ['name' => 'Updated name', 'position' => 1]],
]);

it('disallows invalid requests', function () {
    // Arrange
    [$user, $tabGroupTwo] = prepareTabGroupUpdateTests('a');

    // Act
    $response = $this->actingAs($user, 'sanctum')->patchJson(
        route('tabGroups.update', ['tab_group_id' => $tabGroupTwo->id]),
        ['position' => -1] // invalid
    );

    // Assert
    $response->assertUnprocessable();
});
