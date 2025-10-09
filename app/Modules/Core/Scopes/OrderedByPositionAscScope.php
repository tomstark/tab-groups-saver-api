<?php

declare(strict_types=1);

namespace App\Modules\Core\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

final readonly class OrderedByPositionAscScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $alias = $model->getTable();
        $builder->orderBy("{$alias}.position");
    }
}
