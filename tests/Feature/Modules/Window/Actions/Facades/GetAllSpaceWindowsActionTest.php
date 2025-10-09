<?php

declare(strict_types=1);

use App\Modules\Window\Actions\Facades\GetAllSpaceWindowsAction;
use App\Modules\Window\Actions\GetAllSpaceWindowsAction as GetAllSpaceWindowsActionClass;
use Illuminate\Support\Facades\Facade;

beforeEach(function () {
    Facade::clearResolvedInstances();
});

test('facade is prepared correctly', function () {
    // Arrange
    $args = ['', []];

    // Act & Assert
    $this->assertFacadePrepared(GetAllSpaceWindowsAction::class, GetAllSpaceWindowsActionClass::class, $args);
});
