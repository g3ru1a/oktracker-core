<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionFactory extends Factory
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
            'total_books' => 0,
            'total_cost' => 0,
            'currency' => $this->faker->currencyCode(),
        ];
    }
}
