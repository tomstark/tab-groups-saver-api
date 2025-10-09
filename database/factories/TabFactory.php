<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Space\Models\Space;
use App\Modules\Tab\Models\Tab;
use App\Modules\TabGroup\Models\TabGroup;
use App\Modules\User\Models\User;
use App\Modules\Window\Models\Window;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tab>
 */
final class TabFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'url' => 'https://' . fake()->words(1, true) . '-example.com',
            'icon' => null, // ToDo
        ];
    }

    /**
     * Caution: Adds multiple models to the DB
     */
    public function prepareForUser(User $user): array
    {
        $space = Space::factory()->for($user)->create();
        $window = Window::factory()->for($space)->create();
        $tabGroup = TabGroup::factory()->for($window)->create();

        return [
            'space' => $space,
            'window' => $window,
            'tabGroup' => $tabGroup,
            'tab' => Tab::factory()->for($tabGroup)->create(),
        ];
    }
}
