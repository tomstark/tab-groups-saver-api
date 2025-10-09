<?php

declare(strict_types=1);

use App\Modules\Core\Actions\ResolveIncludesAction;
use Tests\Support\Fakes\FakeRouteIncludables;

it("returns only relevant values intersecting with 'includable'", function () {
    // Arrange
    $includable = new FakeRouteIncludables;

    // Act
    $result = app(ResolveIncludesAction::class)->run(['hello-world', 'lorem.ipsum', 'not in the includable'], $includable);

    // Assert
    expect($result)->toEqual(['helloWorld', 'dolor.sitAmet']);
});

it("returns an empty array if no items match keys in 'includable'", function () {
    // Arrange & Act
    $result = app(ResolveIncludesAction::class)->run(['not', 'in the', 'includable'], new FakeRouteIncludables);

    // Assert
    expect($result)->toEqual([]);
});

it('returns an empty array if no potential includables passed', function () {
    // Arrange & Act
    $result = app(ResolveIncludesAction::class)->run([], new FakeRouteIncludables);

    // Assert
    expect($result)->toEqual([]);
});
