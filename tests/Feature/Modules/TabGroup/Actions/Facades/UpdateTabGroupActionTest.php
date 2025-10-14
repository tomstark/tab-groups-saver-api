<?php

declare(strict_types=1);

use App\Modules\Tab\Models\Tab;
use App\Modules\TabGroup\Actions\Facades\UpdateTabGroupAction;
use App\Modules\TabGroup\Actions\UpdateTabGroupAction as UpdateTabGroupActionClass;
use App\Modules\TabGroup\DTOs\UpdateTabGroupDto;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Facade;

beforeEach(function () {
    Facade::clearResolvedInstances();
});

test('facade is prepared correctly', function () {
    // Arrange
    $user = User::factory()->create();
    ['tabGroup' => $tabGroup] = Tab::factory()->prepareForUser($user);

    $args = [$user, UpdateTabGroupDto::fromArray(['id' => $tabGroup->id])];

    // Mock expectations (Assert)
    $accessorMock = Mockery::mock(new UpdateTabGroupActionClass);
    $accessorMock->expects('run')->with(...$args)->andReturns($tabGroup);
    app()->instance(UpdateTabGroupActionClass::class, $accessorMock);

    // Act
    UpdateTabGroupAction::run(...$args);
});
