<?php

declare(strict_types=1);

namespace App\Modules\Core\Enums;

enum RelationName: string
{
    case Space = 'space';
    case Spaces = 'spaces';
    case Tab = 'tab';
    case TabGroup = 'tabGroup';
    case TabGroups = 'tabGroups';
    case Tabs = 'tabs';
    case User = 'user';
    case Window = 'window';
    case Windows = 'windows';
}
