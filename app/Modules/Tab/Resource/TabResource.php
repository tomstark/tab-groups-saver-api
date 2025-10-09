<?php

declare(strict_types=1);

namespace App\Modules\Tab\Resource;

use App\Modules\Core\Traits\ResourceResponseWithDataTrait;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $id
 * @property-read string $title
 * @property-read string $url
 * @property-read string $icon
 * @property-read int $position
 * @property-read CarbonImmutable $updated_at
 */
final class TabResource extends JsonResource
{
    use ResourceResponseWithDataTrait;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
            'icon' => $this->icon,
            'position' => $this->position,
            'updated_at' => $this->updated_at,
        ];
    }
}
