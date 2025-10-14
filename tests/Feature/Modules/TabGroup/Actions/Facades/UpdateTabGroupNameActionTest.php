<?php

declare(strict_types=1);

use App\Modules\Tab\Models\Tab;
use App\Modules\TabGroup\Actions\Facades\UpdateTabGroupNameAction;
use App\Modules\TabGroup\Actions\UpdateTabGroupNameAction as UpdateTabGroupNameActionClass;
use App\Modules\TabGroup\Models\TabGroup;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Facade;

beforeEach(function () {
    Facade::clearResolvedInstances();
});

test('facade is prepared correctly', function () {
    // Arrange
    $user = User::factory()->create();
    /** @var TabGroup $tabGroup */
    ['tabGroup' => $tabGroup] = Tab::factory()->prepareForUser($user);

    $args = [$tabGroup, 'New tab group name'];

    // Mock expectations (Assert)
    $accessorMock = Mockery::mock(new UpdateTabGroupNameActionClass);
    $accessorMock->expects('run')->with(...$args)->andReturns($tabGroup);
    app()->instance(UpdateTabGroupNameActionClass::class, $accessorMock);

    // Act
    UpdateTabGroupNameAction::run(...$args);
});
