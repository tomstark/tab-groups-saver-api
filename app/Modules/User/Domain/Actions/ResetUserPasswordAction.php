<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Actions;

use App\Modules\User\Domain\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class ResetUserPasswordAction
{
    /**
     * @throws ValidationException
     */
    public function run(string $token, string $email, string $plainPassword): void
    {
        $passwordResetCredentials = [
            'token' => $token,
            'email' => $email,
            'password' => $plainPassword,
        ];

        $status = Password::reset(
            $passwordResetCredentials,
            static function (User $user) use ($plainPassword): void {
                $user
                    ->forceFill(['password' => Hash::make($plainPassword), 'remember_token' => Str::random(60)])
                    ->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            /** @var string $status */
            throw ValidationException::withMessages(['email' => [__($status)]]);
        }
    }
}
