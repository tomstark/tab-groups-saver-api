<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Window\Models\Window;
use Illuminate\Database\Seeder;

final class WindowSeeder extends Seeder
{
    public function run(array $spaces): void
    {
        $windowOneId = '0199a964-8063-7151-ab5e-d7de81ae4023';
        $spaceOne = $spaces[0];
        $windows = [];

        $windows[] = Window::factory()
            ->for($spaceOne)
            ->create([
                'id' => $windowOneId,
                'name' => 'Window one',
                'position' => 1,
            ]);

        foreach (range(2, 4) as $position) {
            $windows[] = Window::factory()
                ->for($spaceOne)
                ->create([
                    'name' => "Window {$position}",
                    'position' => $position,
                ]);
        }

        $this->callWith(TabGroupSeeder::class, ['windows' => $windows]);
    }
}
