<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
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
            'started_at' => fake()->dateTimeBetween('-3 days', 'now')->format('Y-m-d'),
            'dued_at' => fake()->dateTimeBetween('now', '+3 days')->format('Y-m-d'),
            'description' => fake()->paragraph(),
        ];
    }
}
