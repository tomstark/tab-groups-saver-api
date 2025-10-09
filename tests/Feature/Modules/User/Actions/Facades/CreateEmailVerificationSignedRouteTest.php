<?php

declare(strict_types=1);

use App\Modules\User\Actions\CreateEmailVerificationSignedRoute as CreateEmailVerificationSignedRouteClass;
use App\Modules\User\Actions\Facades\CreateEmailVerificationSignedRoute;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Facade;

beforeEach(function () {
    Facade::clearResolvedInstances();
});

test('facade is prepared correctly', function () {
    // Arrange
    $args = [User::factory()->create()];

    // Act & Assert
    $this->assertFacadePrepared(
        CreateEmailVerificationSignedRoute::class,
        CreateEmailVerificationSignedRouteClass::class,
        $args,
    );
});
