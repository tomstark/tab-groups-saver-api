<?php

declare(strict_types=1);

namespace App\Modules\Core\Actions\Facades;

use App\Modules\Core\Traits\MockableFinalFacadeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Spatie\EloquentSortable\Sortable;

/**
 * @template TModel of Sortable&Model
 *
 * @mixin \App\Modules\Core\Actions\UpdateModelPositionAction<TModel>
 */
final class UpdateModelPositionAction extends Facade
{
    use MockableFinalFacadeTrait;

    protected static function getFacadeAccessor(): string
    {
        return \App\Modules\Core\Actions\UpdateModelPositionAction::class;
    }
}
