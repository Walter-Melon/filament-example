<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'position' => $this->faker->numberBetween(1, 80),
            'active' => $this->faker->boolean(70),
            'url' => $this->faker->url(),
            'new_tab' => $this->faker->boolean(20),
        ];
    }
}
