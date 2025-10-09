<?php

declare(strict_types=1);

use App\Modules\Core\Enums\RelationName;
use App\Modules\Tab\Models\Tab;
use App\Modules\User\Models\User;
use App\Modules\Window\Resources\WindowResource;

test('returns expected data', function (bool $withTabGroupsLoaded, array $expectedData) {
    // Arrange
    ['window' => $window] = Tab::factory()->prepareForUser(User::factory()->create());

    if ($withTabGroupsLoaded) {
        $window->load(RelationName::TabGroups->value);
    }

    // Act
    $responseData = WindowResource::make($window)->toResponse(request())->getData();

    // Assert
    expect($responseData)->toHaveExactKeys(['data', 'success'])
        ->and($responseData->data)->toHaveExactKeys($expectedData);
})->with(function () {
    $baseData = ['id', 'name', 'position', 'updated_at'];

    return [
        'without tab groups loaded' => ['withTabGroupsLoaded' => false, 'expectedData' => $baseData],
        'with tab groups loaded' => ['withTabGroupsLoaded' => true, 'expectedData' => [...$baseData, 'tab_groups']],
    ];
});
