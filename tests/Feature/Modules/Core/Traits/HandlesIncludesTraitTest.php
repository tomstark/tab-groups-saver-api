<?php

declare(strict_types=1);

use App\Modules\Core\Contracts\Includable;
use App\Modules\Core\Traits\HandlesIncludesTrait;
use Illuminate\Http\Request;

beforeEach(function () {
    $this->usingTrait = new class
    {
        use HandlesIncludesTrait;
    };
    $this->mockRequest = Mockery::mock(Request::class);
    $this->mockIncludable = Mockery::mock(Includable::class);
});

test('resolves a comma separated list of includes', function () {
    // Arrange
    $this->mockRequest->expects('query')->with('include')->andReturns('lorem,ipsum');
    $this->mockIncludable->expects('map')->andReturns(['lorem' => 'loremValue']);

    // Act
    $result = $this->usingTrait::getIncludesFromRequest($this->mockRequest, $this->mockIncludable);

    // Assert
    expect($result)->toEqual(['loremValue']);
});

test("returns an empty array when no 'include' query parameter data", function () {
    // Arrange
    $this->mockRequest->expects('query')->with('include')->andReturns(null);

    // Act
    $result = $this->usingTrait::getIncludesFromRequest($this->mockRequest, $this->mockIncludable);

    // Assert
    expect($result)->toEqual([]);
});
