<?php

declare(strict_types=1);

namespace App\Modules\User\Actions\Facades;

use App\Modules\Core\Traits\MockableFinalFacadeTrait;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin \App\Modules\User\Actions\CreateEmailVerificationSignedRoute
 */
final class CreateEmailVerificationSignedRoute extends Facade
{
    use MockableFinalFacadeTrait;

    protected static function getFacadeAccessor(): string
    {
        return \App\Modules\User\Actions\CreateEmailVerificationSignedRoute::class;
    }
}
