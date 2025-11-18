<?php

declare(strict_types=1);

use App\Modules\Core\Actions\Facades\UpdateModelPositionAction;
use App\Modules\Space\Actions\Facades\UpdateSpaceNameAction;
use App\Modules\Space\Actions\UpdateSpaceAction;
use App\Modules\Space\DTOs\UpdateSpaceDto;
use App\Modules\Space\Models\Space;
use App\Modules\User\Models\User;

it('uses UpdateModelPositionAction when position data passed', function (bool $argsIncludesPosition) {
    // Arrange
    $user = User::factory()->create();
    $space = Space::factory()->for($user)->create();

    $spy = UpdateModelPositionAction::spy();

    $positionData = $argsIncludesPosition ? ['position' => 1] : [];
    $updateSpaceDto = UpdateSpaceDto::fromArray(['slug' => $space->slug, ...$positionData]);

    // Act
    app(UpdateSpaceAction::class)->run($user, $updateSpaceDto);

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

it('uses UpdateSpaceNameAction when changed name data passed', function (bool $argsIncludesName) {
    // Arrange
    $user = User::factory()->create();
    $space = Space::factory()->for($user)->create();

    $mock = UpdateSpaceNameAction::partialMock();
    if ($argsIncludesName) {
        // Mock expectations (Assert) - Must be a mock expectation as opposed to a Spy in this instance due to the
        // return value type issue with final class mocking. The desired check / 'assertion' is still achieved.
        // (https://docs.mockery.io/en/latest/reference/final_methods_classes.html)
        $mock->expects('run')->andReturns($space);
    }

    $nameData = $argsIncludesName ? ['name' => 'Updated name 1'] : [];
    $updateSpaceDto = UpdateSpaceDto::fromArray(['slug' => $space->slug, ...$nameData]);

    // Act
    app(UpdateSpaceAction::class)->run($user, $updateSpaceDto);

    // Assert
    if (! $argsIncludesName) {
        $mock->shouldNotHaveReceived('run');
    }
})->with([
    ['argsIncludesName' => false],
    ['argsIncludesName' => true],
]);
