<?php

declare(strict_types=1);

use App\Modules\Core\Actions\Facades\UpdateModelPositionAction;
use App\Modules\Tab\Models\Tab;
use App\Modules\TabGroup\Actions\Facades\UpdateTabGroupNameAction;
use App\Modules\TabGroup\Actions\UpdateTabGroupAction;
use App\Modules\TabGroup\DTOs\UpdateTabGroupDto;
use App\Modules\TabGroup\Models\TabGroup;
use App\Modules\User\Models\User;

it('uses UpdateModelPositionAction when position data passed', function (bool $argsIncludesPosition) {
    // Arrange
    $user = User::factory()->create();
    /** @var TabGroup $tabGroup */
    ['tabGroup' => $tabGroup] = Tab::factory()->prepareForUser($user);

    $spy = UpdateModelPositionAction::spy();

    $positionData = $argsIncludesPosition ? ['position' => 1] : [];
    $updateTabGroupDto = UpdateTabGroupDto::fromArray(['id' => $tabGroup->id, ...$positionData]);

    // Act
    app(UpdateTabGroupAction::class)->run($user, $updateTabGroupDto);

    // Assert
    if ($argsIncludesPosition) {
        $spy->shouldHaveReceived('run')->once();

        return;
    }

    $spy->shouldNotHaveReceived('run');
})->with([
    ['argsIncludesPosition' => false],
    ['argsIncludesPosition' => true],
]);

it('uses UpdateTabGroupNameAction when changed name data passed', function (bool $argsIncludesName) {
    // Arrange
    $user = User::factory()->create();
    /** @var TabGroup $tabGroup */
    ['tabGroup' => $tabGroup] = Tab::factory()->prepareForUser($user);

    $mock = UpdateTabGroupNameAction::partialMock();
    if ($argsIncludesName) {
        // Mock expectations (Assert) - Must be a mock expectation as opposed to a Spy in this instance due to the
        // return value type issue with final class mocking. The desired check / 'assertion' is still achieved.
        // (https://docs.mockery.io/en/latest/reference/final_methods_classes.html)
        $mock->expects('run')->andReturns($tabGroup);
    }

    $nameData = $argsIncludesName ? ['name' => 'Updated name 1'] : [];
    $updateTabGroupDto = UpdateTabGroupDto::fromArray(['id' => $tabGroup->id, ...$nameData]);

    // Act
    app(UpdateTabGroupAction::class)->run($user, $updateTabGroupDto);

    // Assert
    if (! $argsIncludesName) {
        $mock->shouldNotHaveReceived('run');
    }
})->with([
    ['argsIncludesName' => false],
    ['argsIncludesName' => true],
]);
