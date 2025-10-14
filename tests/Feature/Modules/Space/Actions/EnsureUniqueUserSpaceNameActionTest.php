<?php

declare(strict_types=1);

use App\Modules\Space\Actions\EnsureUniqueUserSpaceNameAction;
use App\Modules\Space\Exceptions\DuplicateUserSpaceNameException;
use App\Modules\Space\Models\Space;
use App\Modules\User\Models\User;

it('allows unique space name', function () {
    // Arrange
    $user = User::factory()->create();
    Space::factory()->for($user)->create(['name' => 'Holiday research']);

    // Act
    app(EnsureUniqueUserSpaceNameAction::class)->run($user, 'Nature images');

    // Assert
})->throwsNoExceptions();

it('throws exception for duplicate space names', function () {
    // Arrange
    $existingSpaceName = 'Holiday research';
    $user = User::factory()->create();
    Space::factory()->for($user)->create(['name' => $existingSpaceName]);

    // Act
    app(EnsureUniqueUserSpaceNameAction::class)->run($user, $existingSpaceName);

    // Assert
})->throws(DuplicateUserSpaceNameException::class);
