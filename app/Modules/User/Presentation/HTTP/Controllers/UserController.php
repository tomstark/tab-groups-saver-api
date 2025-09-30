<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\HTTP\Controllers;

use App\Modules\Core\HTTP\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UserController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
