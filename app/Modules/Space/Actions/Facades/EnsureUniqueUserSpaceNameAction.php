<?php

declare(strict_types=1);

namespace App\Modules\Space\Actions\Facades;

use App\Modules\Core\Traits\MockableFinalFacadeTrait;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin \App\Modules\Space\Actions\EnsureUniqueUserSpaceNameAction
 */
final class EnsureUniqueUserSpaceNameAction extends Facade
{
    use MockableFinalFacadeTrait;

    protected static function getFacadeAccessor(): string
    {
        return \App\Modules\Space\Actions\EnsureUniqueUserSpaceNameAction::class;
    }
}
