<?php

declare(strict_types=1);

use App\Modules\Core\Actions\Facades\ResolveIncludesAction;
use App\Modules\Core\Actions\ResolveIncludesAction as ResolveIncludesActionClass;
use App\Modules\Core\Contracts\Includable;
use Illuminate\Support\Facades\Facade;

beforeEach(function () {
    Facade::clearResolvedInstances();
});

test('facade is prepared correctly', function () {
    // Arrange
    $mockIncludable = Mockery::mock(Includable::class)->expects('map')->andReturns([])->getMock();
    $args = [[], $mockIncludable];

    // Act & Assert
    $this->assertFacadePrepared(ResolveIncludesAction::class, ResolveIncludesActionClass::class, $args);
});
