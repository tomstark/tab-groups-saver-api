<?php

declare(strict_types=1);

namespace App\Modules\TabGroup\Builders;

use App\Modules\TabGroup\Models\TabGroup;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of TabGroup
 *
 * @extends Builder<TModelClass>
 */
final class TabGroupQueryBuilder extends Builder
{
    // ToDo
    // public function forWindowId(string $windowId): self
    // {
    //     return $this->where('window_id', $windowId);
    // }
}
