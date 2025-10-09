<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Window\Models\Window;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Window>
 */
final class WindowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Window ' . fake()->words(1, true),
        ];
    }
}
