<?php

declare(strict_types=1);

use App\Modules\Core\Traits\ResourceResponseWithDataTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use Tests\Support\Fakes\FakeResource;

function getIndividualResourceResponseData(): array
{
    // Arrange
    $dataForResource = (object) ['id' => 'abc123', 'name' => 'Lorem'];

    // Act
    $resource = FakeResource::make($dataForResource);
    $result = $resource->toResponse(request())->getData();

    return [$result, $dataForResource];
}

it("adds expected 'with' data to the Resource response", function () {
    // Arrange
    [$result, $dataForResource] = getIndividualResourceResponseData();

    // Assert
    expect($result)->toHaveExactKeys(['data', 'success'])
        ->and($result->success)->toEqual(true)
        ->and($result->data)->toEqual($dataForResource);
});

it("adds the same 'with' data to both individual and collection based Resource responses", function () {
    // Arrange
    [$individualResult] = getIndividualResourceResponseData();
    $collectionData = collect(
        [(object) ['id' => 'abc123', 'name' => 'Lorem'], (object) ['id' => 'def456', 'name' => 'Ipsum']],
    );

    // Act
    $collectionResource = FakeResource::collection($collectionData);
    $collectionResult = $collectionResource->toResponse(request())->getData();

    // Assert
    $collectionResultKeys = array_keys((array) $collectionResult);

    expect($collectionResultKeys)->toBe(array_keys((array) $individualResult))
        ->and($collectionResultKeys)->toBe(['data', 'success']);
});

it('preserves ResourceCollection keys based on original Resource property', function () {
    // Arrange
    $data = collect(['hello' => 'world']);

    $unsetPreserveKeysResource = new class([]) extends JsonResource
    {
        use ResourceResponseWithDataTrait;
    };

    $setTruePreserveKeysResource = new class([]) extends JsonResource
    {
        use ResourceResponseWithDataTrait;

        public bool $preserveKeys = true;
    };

    $setFalsePreserveKeysResource = new class([]) extends JsonResource
    {
        use ResourceResponseWithDataTrait;

        public bool $preserveKeys = false;
    };

    // Act
    $unsetPreserveKeysCollectionResource = $unsetPreserveKeysResource::collection($data);
    $setTruePreserveKeysCollectionResource = $setTruePreserveKeysResource::collection($data);
    $setFalsePreserveKeysCollectionResource = $setFalsePreserveKeysResource::collection($data);

    // Assert
    expect($unsetPreserveKeysCollectionResource->preserveKeys)->toBeFalse()
        ->and($setTruePreserveKeysCollectionResource->preserveKeys)->toBeTrue()
        ->and($setFalsePreserveKeysCollectionResource->preserveKeys)->toBeFalse();
});
