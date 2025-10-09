<?php

declare(strict_types=1);

namespace App\Modules\Window\Actions;

use App\Modules\Window\Models\Window;
use Illuminate\Support\Collection;

final readonly class GetAllSpaceWindowsAction
{
    /**
     * @param  string[]  $includeRelations
     * @return Collection<int, Window>
     */
    public function run(string $spaceId, array $includeRelations): Collection
    {
        return Window::query()
            ->forSpaceId($spaceId)
            ->with($includeRelations)
            ->get();
    }
}
