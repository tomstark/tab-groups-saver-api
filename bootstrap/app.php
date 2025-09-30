<?php

declare(strict_types=1);

use App\Modules\Core\HTTP\Responses\Formatters\ExceptionResponseFormatter;
use App\Modules\Core\HTTP\Responses\Formatters\JsonProblemDetailsResponseFormatter;
use App\Modules\User\Presentation\HTTP\Middleware\DenyIfUserAuthenticated;
use App\Modules\User\Presentation\HTTP\Middleware\DenyIfUserEmailNotVerified;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'verified' => DenyIfUserEmailNotVerified::class,
            'guest' => DenyIfUserAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function (Response $response, Throwable $e, Request $request): Response {
            /** @var ExceptionResponseFormatter $jsonProblemDetailsFormatter */
            $jsonProblemDetailsFormatter = app(JsonProblemDetailsResponseFormatter::class);

            if ($jsonProblemDetailsFormatter->supports($response, $e)) {
                return $jsonProblemDetailsFormatter->format($response, $e, $request);
            }

            return $response;
        });
    })->create();
