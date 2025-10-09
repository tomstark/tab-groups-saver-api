<?php

declare(strict_types=1);

use App\Modules\Core\Enums\RelationName;
use App\Modules\Space\Models\Space;
use App\Modules\Tab\Models\Tab;
use App\Modules\User\Models\User;
use App\Modules\Window\Actions\GetAllSpaceWindowsAction;
use App\Modules\Window\Models\Window;
use Illuminate\Database\Eloquent\RelationNotFoundException;

it("gets all windows for a user's space", function () {
    // Arrange
    Window::factory()->for(Space::factory()->for(User::factory()->create())->create())->create(); // another user's data

    $userWindowsCount = 4;
    $user = User::factory()->create();
    $userSpace = Space::factory()->for($user)->create();
    $userWindows = Window::factory()->for($userSpace)->count($userWindowsCount)->create();

    // Act
    $collection = app(GetAllSpaceWindowsAction::class)->run($userSpace->id, []);

    // Assert
    expect(Window::all())->toHaveCount(5)
        ->and($collection)->toHaveCount($userWindowsCount)
        ->and($collection->pluck('id'))->toEqual($userWindows->pluck('id'));
});

it('eager loads relations utilizing nested relations via dot notation', function (array $includeRelations) {
    // Arrange
    ['space' => $space] = Tab::factory()->prepareForUser(User::factory()->create());

    // Act
    $collection = app(GetAllSpaceWindowsAction::class)->run($space->id, $includeRelations);

    // Assert
    expect($collection)->not->toBeEmpty();

    /** @var Window $first */
    $first = $collection->first();

    if ($includeRelations === []) {
        expect(array_keys($first->getRelations()))->toBeEmpty();

        return;
    }

    foreach ($includeRelations as $relation) {
        $parts = explode('.', $relation);
        $model = $first;

        foreach ($parts as $part) {
            expect($model->relationLoaded($part))->toBeTrue();
            $model = $model->{$part}->first();
        }
    }
})->with([
    [[]],
    [[RelationName::TabGroups->value]],
    [[RelationName::TabGroups->value . '.' . RelationName::Tabs->value]],
]);

it('throws an exception if unknown relation passed', function () {
    // Arrange
    $user = User::factory()->create();
    $userSpace = Space::factory()->for($user)->create();
    Window::factory()->for($userSpace)->count(1)->create();

    // Act
    app(GetAllSpaceWindowsAction::class)->run($userSpace->id, ['not a relation']);

    // Assert
})->throws(RelationNotFoundException::class);
