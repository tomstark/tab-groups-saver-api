<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\HTTP\Controllers\Auth;

use App\Modules\Core\HTTP\Controllers\Controller;
use App\Modules\User\Domain\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    public function sanctumTest(Request $request): Response
    {
        // ToDo - just a test, remove when ready
        // ray('sanctumTest');
        // $xDebugTest = true;
        // $request->validate([
        //     'email' => ['required', 'email'],
        //     'password' => ['required'],
        // ]);
        // abort(404);
        // throw new \Exception('Ooops', 409);
        // throw new BadRequestHttpException;
        return response(['a' => 'Hello']);
    }

    public function destroy(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
