<?php

declare(strict_types=1);

use App\Modules\Space\Actions\EnsureUniqueUserSpaceNameAction as EnsureUniqueUserSpaceNameActionClass;
use App\Modules\Space\Actions\Facades\EnsureUniqueUserSpaceNameAction;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Facade;

beforeEach(function () {
    Facade::clearResolvedInstances();
});

test('facade is prepared correctly', function () {
    // Arrange
    $args = [User::factory()->create(), 'Lorem'];

    // Act & Assert
    $this->assertFacadePrepared(EnsureUniqueUserSpaceNameAction::class, EnsureUniqueUserSpaceNameActionClass::class, $args);
});
