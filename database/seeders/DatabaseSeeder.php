<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Testy Tester',
            'email' => 'test@test.test',
            'password' => bcrypt('testing123'),
        ]);

        \App\Models\User::factory(10)->create();
    }
}
