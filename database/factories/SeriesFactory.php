<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SeriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(2),
            'language' => $this->faker->languageCode(),
            'cover_url' => $this->faker->imageUrl(),
            'publisher' => $this->faker->company(),
            'summary' => $this->faker->paragraph(3),
            'authors' => json_encode([$this->faker->name(), $this->faker->name()]),
            'kind' => 'Novel'
        ];
    }
}
