<?php

declare(strict_types=1);

use App\Modules\Core\Actions\UpdateModelPositionAction;
use App\Modules\Core\Exceptions\PositionOutsideRangeException;
use App\Modules\Space\Models\Space;
use App\Modules\User\Models\User;

// NOTE Eloquent Sortable plugin auto-assigns position to newly created models

it("moves a user's model to other positions without affecting other users' models", function (int $repositionTo, array $idPositionsBeforeAfter) {
    // Arrange
    $otherUser = User::factory()->create();
    Space::factory()->for($otherUser)->count(2)->create(); // Another user's models

    $focusedUser = User::factory()->create();
    Space::factory()->for($focusedUser)->count(5)->create();
    $model = $focusedUser->spaces()->where('position', 3)->firstOrFail(); // Always starts with the item in position 3

    $beforeOtherUserSpaceIds = $otherUser->spaces->pluck('id');
    $beforeSpaceIds = $focusedUser->spaces->pluck('id');

    // Act
    app(UpdateModelPositionAction::class)->run($model, $repositionTo);

    $afterOtherUserSpaceIds = $otherUser->refresh()->spaces->pluck('id');
    $afterSpaceIds = $focusedUser->refresh()->spaces->pluck('id');

    // Assert
    expect($beforeOtherUserSpaceIds)->toEqual($afterOtherUserSpaceIds);

    $positionAsIndex = static fn (int $position) => $position - 1;
    foreach ($idPositionsBeforeAfter as $idPositionBeforeAfter) {
        ['before' => $idPositionBefore, 'after' => $idPositionAfter] = $idPositionBeforeAfter;
        expect($beforeSpaceIds[$positionAsIndex($idPositionBefore)])
            ->toBe($afterSpaceIds[$positionAsIndex($idPositionAfter)]);
    }
})->with([
    [
        'repositionTo' => 2, // another 'middle position'
        'idPositionsBeforeAfter' => [
            ['before' => 1, 'after' => 1],
            ['before' => 2, 'after' => 3],
            ['before' => 3, 'after' => 2], // $model
            ['before' => 4, 'after' => 4],
            ['before' => 5, 'after' => 5],
        ],
    ],
    [
        'repositionTo' => 1, // start
        'idPositionsBeforeAfter' => [
            ['before' => 1, 'after' => 2],
            ['before' => 2, 'after' => 3],
            ['before' => 3, 'after' => 1], // $model
            ['before' => 4, 'after' => 4],
            ['before' => 5, 'after' => 5],
        ],
    ],
    [
        'repositionTo' => 5, // end
        'idPositionsBeforeAfter' => [
            ['before' => 1, 'after' => 1],
            ['before' => 2, 'after' => 2],
            ['before' => 3, 'after' => 5], // $model
            ['before' => 4, 'after' => 3],
            ['before' => 5, 'after' => 4],
        ],
    ],
]);

it('throws an exception if position below range', function () {
    // Arrange
    $sortableModel = Space::factory()->for(User::factory()->create())->create();

    // Act
    app(UpdateModelPositionAction::class)->run($sortableModel, -1);

    // Assert
})->throws(PositionOutsideRangeException::class, PositionOutsideRangeException::UNDER_VALID_RANGE_MESSAGE);

it('throws an exception if position above range', function () {
    // Arrange
    $sortableModel = Space::factory()->for(User::factory()->create())->create();

    // Act
    app(UpdateModelPositionAction::class)->run($sortableModel, 2); // There's only one model, yet the new position is 2

    // Assert
})->throws(PositionOutsideRangeException::class, PositionOutsideRangeException::ABOVE_VALID_RANGE_MESSAGE);
