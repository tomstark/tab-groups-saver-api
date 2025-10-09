<?php

declare(strict_types=1);

namespace App\Modules\Window\HTTP\Controllers;

use App\Modules\Core\HTTP\Controllers\Controller;
use App\Modules\Window\Actions\Facades\GetAllSpaceWindowsAction;
use App\Modules\Window\Includables\WindowIncludables;
use App\Modules\Window\Resources\WindowResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

final class WindowController extends Controller
{
    public function index(Request $request, string $spaceId, WindowIncludables $includables): Responsable
    {
        $includes = self::getIncludesFromRequest($request, $includables);
        $windows = GetAllSpaceWindowsAction::run($spaceId, $includes);

        return WindowResource::collection($windows);
    }

    // ToDo
    // public function store() {}
    // public function update() {}
    // public function destroy() {}
}
