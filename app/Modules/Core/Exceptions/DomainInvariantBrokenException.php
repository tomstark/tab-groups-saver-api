<?php

declare(strict_types=1);

namespace App\Modules\Core\Exceptions;

use Exception;

abstract class DomainInvariantBrokenException extends Exception
{
    /** @var int */
    public $code = 422;

    protected string $userMessage = 'Cannot process this request due to a domain rule conflict.';

    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? $this->userMessage);
    }
}
