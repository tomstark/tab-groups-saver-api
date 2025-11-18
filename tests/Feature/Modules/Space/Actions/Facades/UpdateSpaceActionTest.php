<?php

declare(strict_types=1);

use App\Modules\Space\Actions\Facades\UpdateSpaceAction;
use App\Modules\Space\Actions\UpdateSpaceAction as UpdateSpaceActionClass;
use App\Modules\Space\DTOs\UpdateSpaceDto;
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

    $args = [$user, UpdateSpaceDto::fromArray(['slug' => $space->slug])];

    // Mock expectations (Assert)
    $accessorMock = Mockery::mock(new UpdateSpaceActionClass);
    $accessorMock->expects('run')->with(...$args)->andReturns($space);
    app()->instance(UpdateSpaceActionClass::class, $accessorMock);

    // Act
    UpdateSpaceAction::run(...$args);
});
