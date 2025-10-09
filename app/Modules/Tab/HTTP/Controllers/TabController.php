<?php

declare(strict_types=1);

namespace App\Modules\Tab\HTTP\Controllers;

use App\Modules\Core\HTTP\Controllers\Controller;
use App\Modules\Tab\Models\Tab;
use App\Modules\Tab\Resource\TabResource;
use App\Modules\User\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

final class TabController extends Controller
{
    public function show(Request $request, string $tabId): Responsable
    {
        /** @var User $user */
        $user = $request->user();

        $tab = Tab::query()->byIdForUserId($tabId, $user->id)->firstOrFail();

        return TabResource::make($tab);
    }

    // ToDo
    // public function index() {}
    // public function store() {}
    // public function update() {}
    // public function destroy() {}
}
