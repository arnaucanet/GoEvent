<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition()
    {
        $ticketCount = $this->faker->numberBetween(1, 4);
        $ticketPrice = $this->faker->randomFloat(2, 10, 80);
        $tickets = [
            ['type' => 'general', 'quantity' => $ticketCount, 'price' => $ticketPrice],
        ];

        $extras = $this->faker->boolean(40)
            ? [['name' => 'Parking', 'price' => 5.0, 'quantity' => 1]]
            : [];

        $insurance = $this->faker->boolean(20);
        $extrasTotal = collect($extras)->sum(fn ($e) => $e['price'] * $e['quantity']);
        $total = $ticketPrice * $ticketCount + $extrasTotal + ($insurance ? 3.0 : 0);

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'event_id' => Event::inRandomOrder()->first()?->id ?? Event::factory(),
            'tickets' => $tickets,
            'extras' => $extras,
            'insurance' => $insurance,
            'email' => $this->faker->safeEmail(),
            'total_price' => round($total, 2),
            'status' => $this->faker->randomElement(['confirmed', 'pending', 'cancelled']),
        ];
    }
}
