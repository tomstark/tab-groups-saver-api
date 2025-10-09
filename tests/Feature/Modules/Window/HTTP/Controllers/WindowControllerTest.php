<?php

declare(strict_types=1);

use App\Modules\Core\Enums\RelationName;
use App\Modules\Space\Models\Space;
use App\Modules\TabGroup\Models\TabGroup;
use App\Modules\User\Models\User;
use App\Modules\Window\Models\Window;

it("has 'api', auth and 'verified' middleware applied", function () {
    $this->assertRouteHasMiddleware('windows.index', ['api', 'auth:sanctum', 'verified']);
});

test("index route returns only the logged in user's windows", function () {
    // Arrange
    Window::factory()->for(Space::factory()->for(User::factory()->create())->create())->create(); // another user's data
    $user = User::factory()->create();
    $userSpace = Space::factory()->for($user)->create();
    $userWindowsCount = 5;
    $userWindows = Window::factory()->for($userSpace)->count($userWindowsCount)->create();

    // Act
    $response = $this->actingAs($user, 'sanctum')->getJson(route('windows.index', ['space_id' => $userSpace->id]));

    // Assert
    $response->assertOk();
    $responseOriginalContent = $response->getOriginalContent();
    expect(Window::all())->toHaveCount(6)
        ->and($responseOriginalContent)->toHaveCount($userWindowsCount)
        ->and($responseOriginalContent)->pluck('id')->toEqual($userWindows->pluck('id'));
});

test("index route handles 'include' query parameter", function (string $includeQueryParam) {
    // Arrange
    $user = User::factory()->create();
    $userSpace = Space::factory()->for($user)->create();
    // Window::factory()->for($userSpace)->create();
    TabGroup::factory()
        ->for(Window::factory()->for($userSpace)->create())
        ->create();
    // Act
    $response = $this->actingAs($user, 'sanctum')->getJson(
        route('windows.index', [
            'space_id' => $userSpace->id,
            'include' => $includeQueryParam,
        ])
    );

    // Assert
    $response->assertOk();

    /** @var Window $responseData */
    $responseData = $response->getOriginalContent()->first();

    if ($includeQueryParam === '') {
        expect($responseData->getRelations())->toBeEmpty();

        return;
    }

    if ($includeQueryParam === 'tab-groups') {
        expect($responseData->relationLoaded(RelationName::TabGroups->value))->toBeTrue();

        return;
    }

    $this->fail('expectation for include required');
})->with([
    [''],
    ['tab-groups'],
]);
