<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'street' => $this->faker->streetName(),
            'house_number' => $this->faker->buildingNumber(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
            'zip_code' => $this->faker->postcode(),
        ];
    }
}
