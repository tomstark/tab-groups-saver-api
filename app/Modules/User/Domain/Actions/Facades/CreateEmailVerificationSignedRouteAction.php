<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Actions\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \App\Modules\User\Domain\Actions\CreateEmailVerificationSignedRouteAction
 */
final class CreateEmailVerificationSignedRouteAction extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Modules\User\Domain\Actions\CreateEmailVerificationSignedRouteAction::class;
    }
}
