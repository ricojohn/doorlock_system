<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RfidCard>
 */
class RfidCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id' => null,
            'card_number' => fake()->unique()->numerify('##########'),
            'type' => fake()->randomElement(['card', 'keyfob']),
            'status' => fake()->randomElement(['active', 'inactive', 'lost', 'stolen']),
            'issued_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'expires_at' => fake()->optional()->dateTimeBetween('now', '+2 years'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
