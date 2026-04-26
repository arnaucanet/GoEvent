<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'event_id' => Event::inRandomOrder()->first()?->id ?? Event::factory(),
            'status' => $this->faker->randomElement(['inscrito', 'cancelado', 'asistido']),
        ];
    }
}
