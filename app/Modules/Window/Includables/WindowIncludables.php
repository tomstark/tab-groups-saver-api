<?php

declare(strict_types=1);

namespace App\Modules\Window\Includables;

use App\Modules\Core\Contracts\Includable;
use App\Modules\Core\Enums\RelationName;

final readonly class WindowIncludables implements Includable
{
    public function map(): array
    {
        return [
            'tab-groups' => RelationName::TabGroups->value,
            'tab-groups.tabs' => RelationName::TabGroups->value . '.' . RelationName::Tabs->value,
        ];
    }
}
