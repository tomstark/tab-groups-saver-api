<?php

declare(strict_types=1);

use App\Modules\Core\HTTP\Responses\Formatters\JsonProblemDetailsResponseFormatter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

it('supports only JsonResponse', function () {
    $formatter = new JsonProblemDetailsResponseFormatter();

    $jsonResponse = new JsonResponse(['foo' => 'bar']);
    $plainResponse = new Response('plain text');

    expect($formatter->supports($jsonResponse, new Exception()))->toBeTrue()
        ->and($formatter->supports($plainResponse, new Exception()))->toBeFalse();
});

it("formats 'problem details' compliant response", function () {
    // "Problem Details for HTTP APIs" - https://datatracker.ietf.org/doc/html/rfc9457
    $formatter = new JsonProblemDetailsResponseFormatter();
    $request = Request::create('/users/123');

    $exception = new NotFoundHttpException('User not found');
    $response = new JsonResponse([], 404);

    $formatted = $formatter->format($response, $exception, $request);

    expect($formatted->status())->toBe(404)
        ->and($formatted->getData(true))->toMatchArray([
            'type' => 'about:blank',
            'title' => 'Not Found',
            'status' => 404,
            'detail' => 'User not found',
            'instance' => '/users/123',
        ]);
});
