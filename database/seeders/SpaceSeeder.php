<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Space\Models\Space;
use Illuminate\Database\Seeder;

final class SpaceSeeder extends Seeder
{
    public function run(array $users): void
    {
        $spaceOneId = '01999bc1-f809-7001-b36c-51081a53cb39';
        $userOne = $users[0];
        $spaces = [];

        $spaces[] = Space::factory()
            ->for($userOne)
            ->create([
                'id' => $spaceOneId,
                'name' => 'Space one',
                'slug' => 'space-one',
                'position' => 1,
            ]);

        foreach (range(2, 5) as $position) {
            $spaces[] = Space::factory()
                ->for($userOne)
                ->create(['position' => $position]);
        }

        $this->callWith(WindowSeeder::class, ['spaces' => $spaces]);
    }
}
