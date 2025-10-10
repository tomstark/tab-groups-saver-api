<?php

declare(strict_types=1);

namespace App\Modules\Space\HTTP\Controllers;

use App\Modules\Core\HTTP\Controllers\Controller;
use App\Modules\Space\Actions\Facades\CreateSpaceAction;
use App\Modules\Space\DTOs\CreateSpaceDto;
use App\Modules\Space\Resources\SpaceResource;
use App\Modules\User\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SpaceController extends Controller
{
    public function index(Request $request): Responsable
    {
        /** @var User $user */
        $user = $request->user();

        $spaces = $user->spaces;

        return SpaceResource::collection($spaces);
    }

    public function store(Request $request): Responsable
    {
        /** @var array{name: string} $spaceData */
        $spaceData = $request->validate([
            'name' => ['required', 'string', 'max:255'], // ToDo - consider more later
        ]);

        /** @var User $user */
        $user = $request->user();

        $newSpace = CreateSpaceAction::run($user, CreateSpaceDto::fromArray($spaceData));

        $resource = SpaceResource::make($newSpace);
        $resource->response()->setStatusCode(Response::HTTP_CREATED);

        return $resource;
    }

    // ToDo
    // public function update() {}
    // public function destroy() {}
}
