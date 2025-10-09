<?php

declare(strict_types=1);

use App\Modules\Space\Models\Space;
use App\Modules\Space\Resources\SpaceResource;
use App\Modules\User\Models\User;

test('returns expected data', function () {
    // Arrange
    $space = Space::factory()->for(User::factory()->create())->create();

    // Act
    $responseData = SpaceResource::make($space)->toResponse(request())->getData();

    // Assert
    expect($responseData)->toHaveExactKeys(['data', 'success'])
        ->and($responseData->data)->toHaveExactKeys(['id', 'name', 'position']);
});
