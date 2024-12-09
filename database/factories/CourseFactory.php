<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true), // A random 3-word title
            'description' => $this->faker->sentence(), // A random sentence as the description
            'duration' => $this->faker->numberBetween(1, 30), // Random duration between 1 and 30 days
        ];
    }
}
