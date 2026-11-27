<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['Electronics', 'Home', 'Fashion', 'Sports', 'Books', 'Other']),
            'condition' => fake()->randomElement(['new', 'like_new', 'good', 'fair']),
            'starting_price' => fake()->randomFloat(2, 1, 1000),
            'current_price' => fake()->randomFloat(2, 1, 1000),
            'image_path' => null,
            'ends_at' => fake()->dateTimeBetween('+1 day', '+30 days'),
            'status' => 'active',
        ];
    }
}
