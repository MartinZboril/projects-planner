<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'dued_at' => fake()->dateTimeBetween('now', '+3 days')->format('Y-m-d'),
            'is_finished' => rand(0, 1),
            'description' => fake()->paragraph(),
        ];
    }
}
