<?php

declare(strict_types=1);

namespace Tests\Support\Fakes;

use App\Modules\Core\Traits\ResourceResponseWithDataTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $id
 * @property-read string $name
 */
final class FakeResource extends JsonResource
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
        ];
    }
}
