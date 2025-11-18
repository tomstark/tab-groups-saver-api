<?php

declare(strict_types=1);

use App\Modules\Core\Exceptions\ModelNameValidationException;
use App\Modules\Space\Actions\UpdateSpaceNameAction;
use App\Modules\Space\Models\Space;
use App\Modules\User\Models\User;

beforeEach(function () {
    // Arrange
    $this->space = Space::factory()->for(User::factory()->create())->create();
});

it('updates a space name', function () {
    // Arrange
    $newName = 'New space name';

    // Act
    $updatedSpace = app(UpdateSpaceNameAction::class)->run($this->space, $newName);

    // Assert
    expect($updatedSpace->name)->toBe($newName);
});

it('throws ModelNameValidationException exception upon rules broken', function (string $newName, string $exceptionMessage) {
    // Act & Assert
    expect(fn () => app(UpdateSpaceNameAction::class)->run($this->space, $newName))
        ->toThrow(ModelNameValidationException::class, $exceptionMessage);
})->with([
    ['newName' => '', 'exceptionMessage' => 'The name field is required.'],
    [
        'newName' => 'This name is too long for a space name',
        'exceptionMessage' => 'The name field must not be greater than 30 characters.',
    ],
    // ToDo - more when ready (scale UpdateSpaceNameAction to use a dedicated validator file or a chosen pattern etc)
]);
