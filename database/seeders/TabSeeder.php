<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Tab\Models\Tab;
use Illuminate\Database\Seeder;

final class TabSeeder extends Seeder
{
    public function run(array $tabGroups): void
    {
        $tabOneId = '0199a964-807e-7336-af5a-25ffec1da8a9';
        $tabGroupOne = $tabGroups[0];

        Tab::factory()
            ->for($tabGroupOne)
            ->create([
                'id' => $tabOneId,
                'title' => 'Tab one',
                'position' => 1,
            ]);

        foreach (range(2, 4) as $position) {
            Tab::factory()
                ->for($tabGroupOne)
                ->create([
                    'title' => "Tab {$position}",
                    'position' => $position,
                ]);
        }
    }
}
