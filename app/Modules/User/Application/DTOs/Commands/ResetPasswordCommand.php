<?php

declare(strict_types=1);

namespace App\Modules\User\Application\DTOs\Commands;

final readonly class ResetPasswordCommand
{
    public function __construct(
        public string $token,
        public string $email,
        public string $plainPassword,
    ) {}
}
