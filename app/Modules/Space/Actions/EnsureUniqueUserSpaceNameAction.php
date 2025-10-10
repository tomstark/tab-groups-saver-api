<?php

declare(strict_types=1);

namespace App\Modules\Space\Actions;

use App\Modules\Space\Exceptions\DuplicateUserSpaceNameException;
use App\Modules\User\Models\User;

final readonly class EnsureUniqueUserSpaceNameAction
{
    /**
     * @throws DuplicateUserSpaceNameException
     */
    public function run(User $user, string $spaceName): void
    {
        if ($user->spaces()->where('name', $spaceName)->exists()) {
            throw new DuplicateUserSpaceNameException;
        }
    }
}
