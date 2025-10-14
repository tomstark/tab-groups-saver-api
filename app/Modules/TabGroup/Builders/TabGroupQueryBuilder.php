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
    /**
     * @return self<TModelClass>
     */
    public function byIdForUserId(string $tabGroupId, string $userId): self
    {
        return $this
            ->where('id', $tabGroupId)
            ->whereHas('window.space.user', static fn (Builder $query) => $query->where('id', $userId));

        // return $this
        //     ->join('windows', 'tab_groups.window_id', '=', 'windows.id')
        //     ->join('spaces', 'windows.space_id', '=', 'spaces.id')
        //     ->join('users', 'spaces.user_id', '=', 'users.id')
        //     ->where([
        //         ['tab_groups.id', '=', $tabGroupId],
        //         ['users.id', '=', $userId],
        //     ]);
    }
}
