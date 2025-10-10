<?php

declare(strict_types=1);

namespace App\Modules\Space\Exceptions;

use App\Modules\Core\Exceptions\DomainInvariantBrokenException;

final class DuplicateUserSpaceNameException extends DomainInvariantBrokenException
{
    protected string $userMessage = 'You already have a space with this name.';
}
