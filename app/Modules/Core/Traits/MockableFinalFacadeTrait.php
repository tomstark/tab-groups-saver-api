<?php

declare(strict_types=1);

namespace App\Modules\Core\Traits;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin Facade
 */
trait MockableFinalFacadeTrait
{
    /**
     * Fixes the issue with mocking / spying on Facade classes that are declared final by passing an object instance
     * into mockery, as per their docs: https://docs.mockery.io/en/latest/reference/final_methods_classes.html
     */
    protected static function getMockableClass(): ?object
    {
        $class = parent::getMockableClass();

        if ($class === null) {
            return null;
        }

        return new $class;
    }
}
