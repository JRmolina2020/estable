<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'price' => 100,
            'price_two' => 100,
            'cost' => 100,
            'stock' => rand(0, 10),
            'categorie_id' => 2,
            'status' => 1
        ];
    }
}
