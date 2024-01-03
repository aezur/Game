<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gladiator>
 */
class GladiatorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'strength' => $this->faker->numberBetween(1, 10),
            'defense' => $this->faker->numberBetween(1, 10),
            'accuracy' => $this->faker->numberBetween(1, 10),
            'evasion' => $this->faker->numberBetween(1, 10),
            'ludus' => null,
        ];
    }
}
