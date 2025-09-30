<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Actions;

use App\Modules\User\Domain\Models\User;
use App\Modules\User\Presentation\HTTP\Enums\AuthRouteNames;
use Illuminate\Support\Facades\URL;

final class CreateEmailVerificationSignedRouteAction
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
