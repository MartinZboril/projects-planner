<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialNetwork>
 */
class SocialNetworkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'website' => fake()->url(),
            'skype' => fake()->url(),
            'linkedin' => fake()->url(),
            'twitter' => fake()->url(),
            'facebook' => fake()->url(),
            'instagram' => fake()->url(),
        ];
    }
}
