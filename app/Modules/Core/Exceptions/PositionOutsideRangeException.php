<?php

declare(strict_types=1);

namespace App\Modules\Core\Exceptions;

final class PositionOutsideRangeException extends BusinessRuleViolationException
{
    public const string BASE_MESSAGE = 'The passed position value is not valid.';

    public const string UNDER_VALID_RANGE_MESSAGE = 'Position value must be greater than zero.';

    public const string ABOVE_VALID_RANGE_MESSAGE = 'Position value is greater than the valid range.';

    protected string $userMessage = self::BASE_MESSAGE;
}
