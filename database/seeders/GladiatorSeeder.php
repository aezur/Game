<?php

namespace Database\Seeders;

use App\Models\Gladiator;
use Illuminate\Database\Seeder;

class GladiatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gladiator::factory()->count(50)->create();
    }
}
