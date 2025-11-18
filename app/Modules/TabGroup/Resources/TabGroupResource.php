<?php

declare(strict_types=1);

namespace App\Modules\TabGroup\Resources;

use App\Modules\Core\Enums\RelationName;
use App\Modules\Core\Traits\ResourceResponseWithDataTrait;
use App\Modules\Tab\Resource\TabResource;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $id
 * @property-read string $name
 * @property-read string $color
 * @property-read int $position
 * @property-read CarbonImmutable $updated_at
 */
final class TabGroupResource extends JsonResource
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
            'color' => $this->color,
            'position' => $this->position,
            'updated_at' => $this->updated_at,
            'tabs' => TabResource::collection($this->whenLoaded(RelationName::Tabs->value)),
        ];
    }
}
