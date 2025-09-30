<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Actions\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \App\Modules\User\Domain\Actions\ResetUserPasswordAction
 */
final class ResetUserPasswordAction extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Modules\User\Domain\Actions\ResetUserPasswordAction::class;
    }
}
