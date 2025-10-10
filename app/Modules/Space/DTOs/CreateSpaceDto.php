<?php

declare(strict_types=1);

namespace App\Modules\Space\DTOs;

/**
 * @property-read string $id
 * @property-read string $name
 * @property-read int $position
 */
final readonly class CreateSpaceDto
{
    public function __construct(
        public string $name,
    ) {}

    /**
     * @param  array{name: string}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
        );
    }
}
