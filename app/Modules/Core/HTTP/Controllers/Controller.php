<?php

declare(strict_types=1);

namespace App\Modules\Core\HTTP\Controllers;

use App\Modules\Core\Traits\HandlesIncludesTrait;

abstract class Controller
{
    use HandlesIncludesTrait;
}
