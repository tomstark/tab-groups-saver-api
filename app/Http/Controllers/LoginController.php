<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class LoginController extends Controller
{
    public function authenticate(Request $request): JsonResponse
    {
        /** @var array{string: string} $credentials */
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            /** @var User $user */
            $user = User::where('email', $credentials)->firstOrFail();

            return response()->json([
                'message' => 'Successfully logged in',
                'data' => $user->createToken('std')->plainTextToken,
            ]);
        }

        return response()->json(['message' => 'Failed to logged in'], 401);
    }

    /**
     * @return array{a: 'Hello'}
     */
    public function sanctumTest(Request $request): array
    {
        return ['a' => 'Hello'];
    }
}
