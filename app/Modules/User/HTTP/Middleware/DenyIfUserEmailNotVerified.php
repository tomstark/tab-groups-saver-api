<?php

declare(strict_types=1);

namespace App\Modules\User\HTTP\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class DenyIfUserEmailNotVerified
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestUser = $request->user();

        // @phpstan-ignore-next-line
        if (! $requestUser || ($requestUser instanceof MustVerifyEmail && ! $requestUser->hasVerifiedEmail())) {
            return response()->json(['message' => 'Please verify your email to allow access.'], 403);
        }

        return $next($request);
    }
}
