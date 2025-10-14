<?php

declare(strict_types=1);

namespace App\Modules\Space\DTOs;

/**
 * @property-read string $name
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
