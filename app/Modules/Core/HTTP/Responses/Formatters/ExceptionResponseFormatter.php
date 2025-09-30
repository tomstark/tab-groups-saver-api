<?php

declare(strict_types=1);

namespace App\Modules\Core\HTTP\Responses\Formatters;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

interface ExceptionResponseFormatter
{
    public function supports(Response $response, Throwable $e): bool;

    public function format(Response $response, Throwable $e, Request $request): Response;
}
