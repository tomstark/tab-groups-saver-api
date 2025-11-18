<?php

declare(strict_types=1);

use App\Modules\Core\Enums\RelationName;
use App\Modules\Tab\Models\Tab;
use App\Modules\TabGroup\Resources\TabGroupResource;
use App\Modules\User\Models\User;

test('returns expected data', function (bool $withTabsLoaded, array $expectedData) {
    // Arrange
    ['tabGroup' => $tabGroup] = Tab::factory()->prepareForUser(User::factory()->create());

    if ($withTabsLoaded) {
        $tabGroup->load(RelationName::Tabs->value);
    }

    // Act
    $responseData = TabGroupResource::make($tabGroup)->toResponse(request())->getData();

    // Assert
    expect($responseData)->toHaveExactKeys(['data', 'success'])
        ->and($responseData->data)->toHaveExactKeys($expectedData);
})->with(function () {
    $baseData = ['id', 'name', 'color', 'position', 'updated_at'];

    return [
        'without tabs loaded' => ['withTabsLoaded' => false, 'expectedData' => $baseData],
        'with tabs loaded' => ['withTabsLoaded' => true, 'expectedData' => [...$baseData, 'tabs']],
    ];
});
