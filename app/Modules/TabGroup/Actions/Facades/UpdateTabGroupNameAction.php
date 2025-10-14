<?php

declare(strict_types=1);

namespace App\Modules\TabGroup\Actions\Facades;

use App\Modules\Core\Traits\MockableFinalFacadeTrait;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin \App\Modules\TabGroup\Actions\UpdateTabGroupNameAction
 */
final class UpdateTabGroupNameAction extends Facade
{
    use MockableFinalFacadeTrait;

    protected static function getFacadeAccessor(): string
    {
        return \App\Modules\TabGroup\Actions\UpdateTabGroupNameAction::class;
    }
}
