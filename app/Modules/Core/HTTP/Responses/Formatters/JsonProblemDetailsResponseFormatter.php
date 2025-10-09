<?php

declare(strict_types=1);

namespace App\Modules\Core\HTTP\Responses\Formatters;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final readonly class JsonProblemDetailsResponseFormatter implements ExceptionResponseFormatter
{
    public function supports(Response $response, Throwable $e): bool
    {
        return $response instanceof JsonResponse;
    }

    /**
     * Inspired by RFC-9457 / RFC-7807 (not strictly implemented)
     *
     * @param  JsonResponse  $response
     * @return JsonResponse
     */
    public function format(Response $response, Throwable $e, Request $request): Response
    {
        $statusCode = $e->getCode() ?: $response->getStatusCode();

        $payload = [
            'type' => 'about:blank', // ToDo
            'title' => Response::$statusTexts[$statusCode] ?? 'Error',
            'status' => $statusCode,
            'detail' => $e->getMessage(),
            'instance' => $request->getRequestUri(),
        ];

        /** @var array<string, string|array<string, string[]>> $originalContent */
        $originalContent = $response->getOriginalContent();
        /** @var array<string, string[]> $errors */
        $errors = $originalContent['errors'] ?? [];

        if (count($errors) > 0) {
            $payload['errors'] = $errors;
        }

        return $response
            ->setData($payload)
            ->setStatusCode((int) $statusCode)
            ->header('Content-Type', 'application/problem+json');
    }
}
