<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(10),
            'details' => $this->faker->text(200),
            'image' => $this->faker->text(150),
            'thumbimage' => $this->faker->text(150),
        ];
    }
}
