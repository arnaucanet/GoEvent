<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventTypeFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Concierto', 'Conferencia', 'Taller', 'Festival', 'Exposición',
                'Deportivo', 'Networking', 'Gastronómico', 'Teatro', 'Familiar',
            ]) . ' ' . $this->faker->randomNumber(3),
            'description' => $this->faker->sentence(),
            'active' => true,
        ];
    }
}
