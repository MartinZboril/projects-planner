<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'estimated_hours' => rand(100, 1000),
            'started_at' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
            'dued_at' => fake()->dateTimeBetween('now', '+1 years')->format('Y-m-d'),
            'budget' => rand(10000, 1000000),
            'description' => fake()->paragraph(),
        ];
    }
}
