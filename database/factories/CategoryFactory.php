<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->sentence(),
            'icon' => $this->faker->randomElement(['music', 'camera', 'briefcase', 'heart', 'palette', 'utensils', 'code', 'cpu']),
            'active' => $this->faker->boolean(85),
        ];
    }
}
