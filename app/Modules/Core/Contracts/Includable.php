<?php

declare(strict_types=1);

namespace App\Modules\Core\Contracts;

interface Includable
{
    /**
     * @return array<string, string>
     */
    public function map(): array;
}
