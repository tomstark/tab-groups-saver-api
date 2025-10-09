<?php

declare(strict_types=1);

namespace App\Modules\Core\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Adds 'with' data to both a Resource and its collection, eliminating the need for a separate ResourceCollection class.
 * Works when using either: MyResource::make($data); or MyResource::collection($data);
 *
 * @mixin JsonResource
 *
 * @property bool $preserveKeys
 */
trait ResourceResponseWithDataTrait
{
    /**
     * @var array<string, mixed>
     */
    protected static array $responseWithData = [
        'success' => true,
    ];

    public static function collection($resource): AnonymousResourceCollection
    {
        $newCollection = static::newCollection($resource);
        $newCollection->with = self::$responseWithData;

        return tap(
            $newCollection,
            static function ($collection): void {
                if (property_exists(static::class, 'preserveKeys')) {
                    $collection->preserveKeys = (new static([]))->preserveKeys === true;
                }
            }
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return self::$responseWithData;
    }
}
