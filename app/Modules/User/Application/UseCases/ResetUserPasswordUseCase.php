<?php

declare(strict_types=1);

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Application\DTOs\Commands\ResetPasswordCommand;
use App\Modules\User\Application\DTOs\Responses\MessageResponse;
use App\Modules\User\Domain\Actions\Facades\ResetUserPasswordAction;
use Illuminate\Validation\ValidationException;

final class ResetUserPasswordUseCase
{
    /**
     * @throws ValidationException
     */
    public function __invoke(ResetPasswordCommand $command): MessageResponse
    {
        ResetUserPasswordAction::run(
            $command->token,
            $command->email,
            $command->plainPassword,
        );

        return new MessageResponse('Successfully reset password');
    }
}
