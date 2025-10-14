<?php

declare(strict_types=1);

use App\Modules\Space\Actions\CreateSpaceAction as CreateSpaceActionClass;
use App\Modules\Space\Actions\Facades\CreateSpaceAction;
use App\Modules\Space\DTOs\CreateSpaceDto;
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
    $args = [$user, CreateSpaceDto::fromArray(['name' => 'My New Space'])];

    // Mock expectations (Assert)
    $accessorMock = Mockery::mock(new CreateSpaceActionClass);
    $accessorMock->expects('run')->with(...$args)->andReturns($space);
    app()->instance(CreateSpaceActionClass::class, $accessorMock);

    // Act
    CreateSpaceAction::run(...$args);
});
