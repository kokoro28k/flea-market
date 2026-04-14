<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => null,
            'name' => $this->faker->sentence(),
            'brand' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'condition' => 1,
            'price' => 1000,
            'image_path' => 'test.jpg',
            'status' => 0,
        ];
    }
}
