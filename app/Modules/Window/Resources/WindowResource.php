<?php

declare(strict_types=1);

namespace App\Modules\Window\Resources;

use App\Modules\Core\Enums\RelationName;
use App\Modules\Core\Traits\ResourceResponseWithDataTrait;
use App\Modules\TabGroup\Resources\TabGroupResource;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $id
 * @property-read string $name
 * @property-read int $position
 * @property-read CarbonImmutable $updated_at
 */
final class WindowResource extends JsonResource
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
            'position' => $this->position,
            'updated_at' => $this->updated_at,
            'tab_groups' => TabGroupResource::collection($this->whenLoaded(RelationName::TabGroups->value)),
        ];
    }
}
