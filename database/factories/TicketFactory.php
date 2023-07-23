<?php

namespace Database\Factories;

use App\Enums\TicketPriorityEnum;
use App\Enums\TicketTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => fake()->word(),
            'type' => fake()->randomElement([TicketTypeEnum::error->value, TicketTypeEnum::help->value, TicketTypeEnum::inovation->value, TicketTypeEnum::other->value]),
            'priority' => fake()->randomElement([TicketPriorityEnum::urgent->value, TicketPriorityEnum::high->value, TicketPriorityEnum::medium->value, TicketPriorityEnum::low->value]),
            'dued_at' => fake()->dateTimeBetween('now', '+3 days')->format('Y-m-d'),
            'message' => fake()->paragraph(),
        ];
    }
}
