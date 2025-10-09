<?php

declare(strict_types=1);

namespace App\Modules\Window\Builders;

use App\Modules\Window\Models\Window;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of Window
 *
 * @extends Builder<TModelClass>
 */
final class WindowQueryBuilder extends Builder
{
    /**
     * @return self<Window>
     */
    public function forSpaceId(string $spaceId): self
    {
        return $this->where('space_id', $spaceId);
    }
}
