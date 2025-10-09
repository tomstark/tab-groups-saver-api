<?php

declare(strict_types=1);

use App\Modules\Tab\Models\Tab;
use App\Modules\Tab\Resource\TabResource;
use App\Modules\User\Models\User;

test('returns expected data', function () {
    // Arrange
    ['tab' => $tab] = Tab::factory()->prepareForUser(User::factory()->create());

    // Act
    $responseData = TabResource::make($tab)->toResponse(request())->getData();

    // Assert
    expect($responseData)->toHaveExactKeys(['data', 'success'])
        ->and($responseData->data)->toHaveExactKeys(['id', 'title', 'url', 'icon', 'position', 'updated_at']);
});
