<?php

declare(strict_types=1);

namespace App\Modules\User\Application\DTOs\Responses;

final readonly class MessageResponse
{
    public function __construct(public string $message) {}
}
