<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\HTTP\Requests;

use App\Modules\User\Application\DTOs\Commands\ResetPasswordCommand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRules;

final class ResetPasswordRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|mixed[]|string>
     */
    public function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRules::defaults()],
            // 'password_confirmation' required in request also (via use of 'confirmed' above)
        ];
    }

    public function toCommand(): ResetPasswordCommand
    {
        return new ResetPasswordCommand(
            $this->string('token')->value(),
            $this->string('email')->value(),
            $this->string('password')->value(),
            // Having passed validation, we don't need 'password_confirmation'
        );
    }
}
