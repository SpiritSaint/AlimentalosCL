<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'avatar' => fake()->imageUrl(),
            'type' => fake()->randomElement(['dog', 'cat']),
            'name' => fake()->name(),
            'description' => fake()->boolean() ? fake()->text() : null,
        ];
    }
}
