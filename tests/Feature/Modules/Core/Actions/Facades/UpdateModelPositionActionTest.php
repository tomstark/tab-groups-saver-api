<?php

declare(strict_types=1);

use App\Modules\Core\Actions\Facades\UpdateModelPositionAction;
use App\Modules\Core\Actions\UpdateModelPositionAction as UpdateModelPositionActionClass;
use App\Modules\Space\Models\Space;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Facade;

beforeEach(function () {
    Facade::clearResolvedInstances();
});

test('facade is prepared correctly', function () {
    // Arrange
    $sortableModel = Space::factory()->for(User::factory()->create())->create();
    $args = [$sortableModel, 1];

    // Act & Assert
    $this->assertFacadePrepared(UpdateModelPositionAction::class, UpdateModelPositionActionClass::class, $args);
});
