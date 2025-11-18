<?php

declare(strict_types=1);

namespace App\Modules\Space\Resources;

use App\Modules\Core\Traits\ResourceResponseWithDataTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $id
 * @property-read string $name
 * @property-read string $slug
 * @property-read int $position
 */
final class SpaceResource extends JsonResource
{
    use ResourceResponseWithDataTrait;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'position' => $this->position,
        ];
    }
}
