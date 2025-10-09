<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\TabGroup\Models\TabGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TabGroup>
 */
final class TabGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'color' => fake()->randomElements(['grey', 'blue'])[0],
        ];
    }
}
