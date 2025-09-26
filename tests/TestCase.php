<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Route;

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
}
