<?php

declare(strict_types=1);

namespace App\Modules\User\HTTP\Controllers\Auth;

use App\Modules\Core\HTTP\Controllers\Controller;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class AuthenticationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        /** @var array{string: string} $credentials */
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            /** @var User $user */
            $user = User::where('email', $credentials)->firstOrFail();

            return response()->json([
                'message' => 'Successfully logged in',
                'token' => $user->createToken('std')->plainTextToken,
            ]);
        }

        return response()->json(['message' => 'Failed to log in'], 401);
    }

    public function destroy(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
