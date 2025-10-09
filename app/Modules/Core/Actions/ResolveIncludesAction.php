<?php

declare(strict_types=1);

namespace App\Modules\Core\Actions;

use App\Modules\Core\Contracts\Includable;

final readonly class ResolveIncludesAction
{
    /**
     * @param  string[]  $potentialIncludes
     * @return string[]
     */
    public function run(array $potentialIncludes, Includable $includable): array
    {
        return array_values(array_intersect_key($includable->map(), array_flip($potentialIncludes)));
    }
}
