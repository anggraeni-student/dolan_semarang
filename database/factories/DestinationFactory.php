<?php

namespace Database\Factories;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Destination>
 */
class DestinationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'category' => fake()->randomElement(['Sejarah', 'Alam', 'Kuliner', 'Religi', 'Edukasi']),
            'price_range' => fake()->randomElement(['Gratis', 'Rp10.000 - Rp20.000', 'Rp20.000 - Rp50.000']),
            'description' => fake()->paragraph(),
            'address' => fake()->streetAddress() . ', Semarang',
            'image_url' => null,
        ];
    }
}
