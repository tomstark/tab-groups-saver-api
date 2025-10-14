<?php

declare(strict_types=1);

namespace App\Modules\TabGroup\HTTP\Controllers;

use App\Modules\Core\HTTP\Controllers\Controller;
use App\Modules\TabGroup\Actions\Facades\UpdateTabGroupAction;
use App\Modules\TabGroup\DTOs\UpdateTabGroupDto;
use App\Modules\TabGroup\Resources\TabGroupResource;
use App\Modules\User\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class TabGroupController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function update(Request $request, string $tabGroupId): Responsable
    {
        /** @var array{id: string, name?: string, position?: int} $tabGroupUpdateData */
        $tabGroupUpdateData = Validator::make(
            array_merge($request->all(), ['id' => $tabGroupId]),
            [
                'id' => ['required', 'uuid'],
                'name' => ['sometimes', 'string', 'min:1', 'max:255'],
                'position' => ['sometimes', 'integer', 'min:1'],
            ]
        )->validate();

        /** @var User $user */
        $user = $request->user();

        $tabGroup = UpdateTabGroupAction::run($user, UpdateTabGroupDto::fromArray($tabGroupUpdateData));

        return TabGroupResource::make($tabGroup);
    }

    // ToDo
    // public function index() {}
    // public function store() {}
    // public function update() {}
    // public function destroy() {}
}
