<?php

namespace Database\Factories;

use App\Models\TravelOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TravelOrder>
 */
class TravelOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(), // Cria um usuário para o pedido
            'requester_name' => $this->faker->name,
            'destination' => $this->faker->city,
            'departure_date' => now()->addDays(1)->toDateString(),
            'return_date' => now()->addDays(10)->toDateString(),
            'status' => \App\Enums\TravelOrderStatus::REQUESTED,
        ];
    }
}
