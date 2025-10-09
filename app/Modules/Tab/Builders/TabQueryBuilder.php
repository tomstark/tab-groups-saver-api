<?php

declare(strict_types=1);

namespace App\Modules\Tab\Builders;

use App\Modules\Tab\Models\Tab;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of Tab
 *
 * @extends Builder<TModelClass>
 */
final class TabQueryBuilder extends Builder
{
    // ToDo
    // public function forTabGroupId(string $tabGroupId): self
    // {
    //     return $this->where('tab_group_id', $tabGroupId);
    // }

    /**
     * @return self<Tab>
     */
    public function byIdForUserId(string $tabId, string $userId): self
    {
        return $this
            ->select(['tabs.id', 'tabs.title', 'tabs.url', 'tabs.icon', 'tabs.position', 'tabs.updated_at'])
            ->join('tab_groups', 'tab_groups.id', '=', 'tabs.tab_group_id')
            ->join('windows', 'windows.id', '=', 'tab_groups.window_id')
            ->join('spaces', 'spaces.id', '=', 'windows.space_id')
            ->join('users', 'users.id', '=', 'spaces.user_id')
            ->where('tabs.id', $tabId)
            ->where('users.id', $userId);

        // Above is an optimized version of ->whereRelation('tabGroup.window.space.user', 'id', $userId);
    }
}
