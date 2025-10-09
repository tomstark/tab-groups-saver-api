<?php

declare(strict_types=1);

namespace App\Modules\Space\HTTP\Controllers;

use App\Modules\Core\HTTP\Controllers\Controller;
use App\Modules\Space\Resources\SpaceResource;
use App\Modules\User\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

final class SpaceController extends Controller
{
    public function index(Request $request): Responsable
    {
        /** @var User $user */
        $user = $request->user();

        $spaces = $user->spaces;

        return SpaceResource::collection($spaces);
    }

    // ToDo
    // public function store() {}
    // public function update() {}
    // public function destroy() {}
}
