<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Route;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param  string[]  $middlewareNames
     */
    protected function assertRouteHasMiddleware(string $routeName, array $middlewareNames): void
    {
        $route = Route::getRoutes()->getByName($routeName);

        $this->assertNotNull(
            $route,
            "Failed asserting that route [{$routeName}] exists."
        );

        $middlewares = $route->gatherMiddleware();

        foreach ($middlewareNames as $middlewareName) {
            $this->assertContains(
                $middlewareName,
                $middlewares,
                "Failed asserting that route [{$routeName}] has middleware [{$middlewareName}]."
            );
        }
    }

    /**
     * 'Accessor' = the class we're creating the facade for
     * @param  class-string  $facadeClassName  - class-string of the actual facade under test
     * @param  class-string  $accessorClassName  - class-string of the Accessor
     */
    protected function assertFacadePrepared(
        string $facadeClassName,
        string $accessorClassName,
        array $args,
        string $methodNameBeingCalled = 'run'
    ): void {
        // Arrange
        app()->instance($accessorClassName, Mockery::spy(new $accessorClassName));

        // Act
        $facadeClassName::$methodNameBeingCalled(...$args);

        // Assert
        app($accessorClassName)->shouldHaveReceived($methodNameBeingCalled)->with(...$args)->once();
    }
}
