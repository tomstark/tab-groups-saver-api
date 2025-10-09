<?php

declare(strict_types=1);

namespace App\Modules\User\HTTP\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final readonly class DenyIfUserAuthenticated
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard()->check()) {
            return response()->json(['message' => 'Already authenticated.'], 403);
        }

        return $next($request);
    }
}
