<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\TabGroup\Models\TabGroup;
use Illuminate\Database\Seeder;

final class TabGroupSeeder extends Seeder
{
    public function run(array $windows): void
    {
        $tabGroupOneId = '0199a964-8071-71fa-8a10-a326c5e24382';
        $windowOne = $windows[0];
        $tabGroups = [];

        $tabGroups[] = TabGroup::factory()
            ->for($windowOne)
            ->create([
                'id' => $tabGroupOneId,
                'name' => 'Tab group one',
                'position' => 1,
            ]);

        foreach (range(2, 4) as $position) {
            $tabGroups[] = TabGroup::factory()
                ->for($windowOne)
                ->create([
                    'name' => "Tab group {$position}",
                    'position' => $position,
                ]);
        }

        $this->callWith(TabSeeder::class, ['tabGroups' => $tabGroups]);
    }
}
