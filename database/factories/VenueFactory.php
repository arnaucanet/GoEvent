<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VenueFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->company() . ' ' . $this->faker->randomElement(['Arena', 'Hall', 'Theatre', 'Center']),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'capacity' => $this->faker->numberBetween(50, 5000),
            'active' => true,
        ];
    }
}
