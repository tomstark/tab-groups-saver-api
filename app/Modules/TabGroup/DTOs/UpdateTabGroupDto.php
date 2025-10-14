<?php

declare(strict_types=1);

namespace App\Modules\TabGroup\DTOs;

/**
 * @property-read string $name
 * @property-read int $position
 */
final readonly class UpdateTabGroupDto
{
    public function __construct(
        public string $id,
        public ?string $name = null,
        public ?string $color = null,
        public ?int $position = null,
    ) {}

    /**
     * @param  array{id: string, name?: string, color?: string, position?: int}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['name'] ?? null,
            $data['color'] ?? null,
            $data['position'] ?? null,
        );
    }
}
