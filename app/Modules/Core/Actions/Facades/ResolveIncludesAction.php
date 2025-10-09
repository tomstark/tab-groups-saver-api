<?php

declare(strict_types=1);

namespace App\Modules\Core\Actions\Facades;

use App\Modules\Core\Traits\MockableFinalFacadeTrait;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin \App\Modules\Core\Actions\ResolveIncludesAction
 */
final class ResolveIncludesAction extends Facade
{
    use MockableFinalFacadeTrait;

    protected static function getFacadeAccessor(): string
    {
        return \App\Modules\Core\Actions\ResolveIncludesAction::class;
    }
}
