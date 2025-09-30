<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\HTTP\Controllers\Auth;

use App\Modules\Core\HTTP\Controllers\Controller;
use App\Modules\User\Domain\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class EmailVerificationNotificationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Already verified']);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'verification-link-sent']);
    }
}
