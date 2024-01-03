<?php

namespace Database\Seeders;

use App\Models\Gladiator;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Testy Tester',
            'email' => 'test@test.test',
            'password' => bcrypt('testing123'),
        ]);

        User::factory(10)->create();
        Gladiator::factory(50)->create();
    }
}
