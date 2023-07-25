<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Timer>
 */
class TimerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'since_at' => fake()->dateTimeBetween('-2 hours', 'now')->format('Y-m-d H:i'),
            'until_at' => fake()->dateTimeBetween('now', '+2 hours')->format('Y-m-d H:i'),
            'note' => fake()->paragraph(),
        ];
    }
}
