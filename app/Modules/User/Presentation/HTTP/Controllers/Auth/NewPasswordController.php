<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\HTTP\Controllers\Auth;

use App\Modules\Core\HTTP\Controllers\Controller;
use App\Modules\User\Application\UseCases\ResetUserPasswordUseCase;
use App\Modules\User\Presentation\HTTP\Requests\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

final class NewPasswordController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function store(ResetPasswordRequest $request): JsonResponse
    {
        $response = (new ResetUserPasswordUseCase)($request->toCommand());

        return response()->json(['message' => $response->message]);
    }
}
