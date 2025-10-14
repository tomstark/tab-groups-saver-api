<?php

declare(strict_types=1);

namespace App\Modules\Core\Actions;

use App\Modules\Core\Exceptions\PositionOutsideRangeException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;

/**
 * @template TModel of Sortable&Model
 */
final readonly class UpdateModelPositionAction
{
    /**
     * @param  TModel  $model
     * @return TModel
     *
     * @throws PositionOutsideRangeException
     */
    public function run(Sortable $model, int $newPosition): Sortable
    {
        if ($newPosition < 1) {
            throw new PositionOutsideRangeException(PositionOutsideRangeException::UNDER_VALID_RANGE_MESSAGE);
        }

        /** @var Builder<TModel> $modelBuilder */
        $modelBuilder = $model->buildSortQuery(); // @phpstan-ignore method.notFound

        if ($newPosition > $modelBuilder->count()) {
            throw new PositionOutsideRangeException(PositionOutsideRangeException::ABOVE_VALID_RANGE_MESSAGE);
        }

        $items = $modelBuilder->ordered()->get();
        $items = $items->reject(fn (Sortable $item): bool => $item->id === $model->id); // @phpstan-ignore property.notFound, property.notFound
        $items->splice($newPosition - 1, 0, [$model]);

        $model->setNewOrder($items->pluck('id')->toArray());
        $model->position = $newPosition; // @phpstan-ignore property.notFound

        return $model;
    }
}
