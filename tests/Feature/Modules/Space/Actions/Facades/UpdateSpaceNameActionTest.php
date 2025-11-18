<?php

declare(strict_types=1);

use App\Modules\Space\Actions\Facades\UpdateSpaceNameAction;
use App\Modules\Space\Actions\UpdateSpaceNameAction as UpdateSpaceNameActionClass;
use App\Modules\Space\Models\Space;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Facade;

beforeEach(function () {
    Facade::clearResolvedInstances();
});

test('facade is prepared correctly', function () {
    // Arrange
    $user = User::factory()->create();
    $space = Space::factory()->for($user)->create();

    $args = [$space, 'New space name'];

    // Mock expectations (Assert)
    $accessorMock = Mockery::mock(new UpdateSpaceNameActionClass);
    $accessorMock->expects('run')->with(...$args)->andReturns($space);
    app()->instance(UpdateSpaceNameActionClass::class, $accessorMock);

    // Act
    UpdateSpaceNameAction::run(...$args);
});
