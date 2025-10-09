<?php

declare(strict_types=1);

namespace App\Modules\Space\Builders;

use App\Modules\Space\Models\Space;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of Space
 *
 * @extends Builder<TModelClass>
 */
final class SpaceQueryBuilder extends Builder
{
    // ToDo
    // public function forUserId(string $userId): self
    // {
    //     return $this->where('user_id', $userId);
    // }
}
