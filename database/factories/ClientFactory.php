<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
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
            'email' => fake()->unique()->safeEmail(),
            'contact_person' => fake()->firstName().' '.fake()->lastName(),
            'contact_email' => fake()->unique()->safeEmail(),
            'mobile' => fake()->phoneNumber(),
            'phone' => fake()->phoneNumber(),
            'note' => fake()->paragraph(),
        ];
    }
}
