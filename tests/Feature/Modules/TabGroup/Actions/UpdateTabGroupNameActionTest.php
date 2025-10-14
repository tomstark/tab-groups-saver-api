<?php

declare(strict_types=1);

use App\Modules\Core\Exceptions\ModelNameValidationException;
use App\Modules\Space\Models\Space;
use App\Modules\TabGroup\Actions\UpdateTabGroupNameAction;
use App\Modules\TabGroup\Models\TabGroup;
use App\Modules\User\Models\User;
use App\Modules\Window\Models\Window;

beforeEach(function () {
    // Arrange
    $user = User::factory()->create();
    $space = Space::factory()->for($user)->create();
    $window = Window::factory()->for($space)->create();
    $this->tabGroup = TabGroup::factory()->for($window)->create(['name' => 'Tab group name']);
});

it('updates a tab group name', function () {
    // Arrange
    $newName = 'New tab group name';

    // Act
    $updatedTabGroup = app(UpdateTabGroupNameAction::class)->run($this->tabGroup, $newName);

    // Assert
    expect($updatedTabGroup->name)->toBe($newName);
});

it('throws ModelNameValidationException exception upon rules broken', function (string $newName, string $exceptionMessage) {
    // Act & Assert
    expect(fn () => app(UpdateTabGroupNameAction::class)->run($this->tabGroup, $newName))
        ->toThrow(ModelNameValidationException::class, $exceptionMessage);
})->with([
    ['newName' => '', 'exceptionMessage' => 'The name field is required.'],
    [
        'newName' => 'This name is too long for a tab group name',
        'exceptionMessage' => 'The name field must not be greater than 30 characters.',
    ],
    // ToDo - more when ready (scale UpdateTabGroupNameAction to use a dedicated validator file or a chosen pattern etc)
]);
