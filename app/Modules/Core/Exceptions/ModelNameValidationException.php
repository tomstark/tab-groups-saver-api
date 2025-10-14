<?php

declare(strict_types=1);

namespace App\Modules\Core\Exceptions;

final class ModelNameValidationException extends BusinessRuleViolationException
{
    public const string BASE_MESSAGE = 'The passed name is invalid.';

    protected string $userMessage = self::BASE_MESSAGE;
}
