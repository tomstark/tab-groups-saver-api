<?php

declare(strict_types=1);

namespace App\Modules\Space\DTOs;

/**
 * @property-read string $name
 * @property-read int $position
 */
final readonly class UpdateSpaceDto
{
    public function __construct(
        public string $slug,
        public ?string $name = null,
        public ?int $position = null,
    ) {}

    /**
     * @param  array{slug: string, name?: string, position?: int}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['slug'],
            $data['name'] ?? null,
            $data['position'] ?? null,
        );
    }
}
