<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\User\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        $userOneId = '01999f75-5b02-7215-9c71-45c226ceeff6';

        $users = [
            User::factory()->create(['id' => $userOneId, 'name' => 'Test User', 'email' => 'test@example.com']),
            User::factory()->create(['name' => 'Lorem User', 'email' => 'lorem@example.com']),
            User::factory()->create(['name' => 'Ipsum User', 'email' => 'ipsum@example.com']),
        ];

        $this->callWith(SpaceSeeder::class, ['users' => $users]);
    }
}
