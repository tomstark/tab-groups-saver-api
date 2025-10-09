<?php

declare(strict_types=1);

namespace App\Modules\Window\Actions\Facades;

use App\Modules\Core\Traits\MockableFinalFacadeTrait;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin \App\Modules\Window\Actions\GetAllSpaceWindowsAction
 */
final class GetAllSpaceWindowsAction extends Facade
{
    use MockableFinalFacadeTrait;

    protected static function getFacadeAccessor(): string
    {
        return \App\Modules\Window\Actions\GetAllSpaceWindowsAction::class;
    }
}
