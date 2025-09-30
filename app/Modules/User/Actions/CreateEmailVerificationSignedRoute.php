<?php

declare(strict_types=1);

namespace App\Modules\User\Actions;

use App\Modules\User\HTTP\Enums\AuthRouteNames;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\URL;

final class CreateEmailVerificationSignedRoute
{
    /**
     * @param  array{}|array{id: string, hash: string}  $parameters  = []
     */
    public function run(User $user, array $parameters = []): string
    {
        return URL::temporarySignedRoute(
            AuthRouteNames::MarkEmailVerified->value,
            now()->addMinutes(60),
            ($parameters !== []) ? $parameters : ['id' => $user->id, 'hash' => sha1($user->email)],
            false
        );
    }
}
