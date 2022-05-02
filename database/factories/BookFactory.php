<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'clean_title' => $this->faker->sentence(2),
            'isbn_10' => $this->faker->isbn10(),
            'isbn_13' => $this->faker->isbn13(),
            'publish_date' => $this->faker->date(),
            'pages' => $this->faker->randomNumber(3),
            'cover_url' => $this->faker->imageUrl(),
            'volume_number' => $this->faker->randomNumber(1),
            'synopsis' => $this->faker->paragraph(),
            'authors' => json_encode([$this->faker->name(), $this->faker->name()]),
            'language' => $this->faker->languageCode(),
            'publisher' => $this->faker->company(),
            'binding' => 'Paperback',
        ];
    }
}
