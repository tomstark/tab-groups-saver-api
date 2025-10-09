<?php

declare(strict_types=1);

namespace App\Modules\Core\Traits;

use App\Modules\Core\Actions\Facades\ResolveIncludesAction;
use App\Modules\Core\Contracts\Includable;
use Illuminate\Http\Request;

trait HandlesIncludesTrait
{
    /**
     * @return string[]
     */
    public static function getIncludesFromRequest(Request $request, Includable $includable): array
    {
        $includes = [];
        $include = $request->query('include');

        if ($include) {
            $requestedIncludes = explode(',', $include);
            $includes = ResolveIncludesAction::run($requestedIncludes, $includable);
        }

        return $includes;
    }
}
