<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemGroup>
 */
class ItemGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->domainWord(),
            'position' => $this->faker->numberBetween(1, 20),
            'active' => $this->faker->boolean(70),
            'only_admin' => $this->faker->boolean(20),
        ];
    }
}
