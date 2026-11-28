<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'destination_id' => \App\Models\Destination::factory(),
            'user_name' => fake()->name(),
            'rating' => fake()->numberBetween(3, 5),
            'comment' => fake()->sentence(10),
            'photo_url' => null,
        ];
    }
}