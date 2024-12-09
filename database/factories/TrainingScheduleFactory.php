<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TrainingSchedule;
use App\Models\Course;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingSchedule>
 */
class TrainingScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(), // Automatically creates a related course
            'start_date' => $this->faker->dateTimeBetween('+1 days', '+10 days'), // Start date in the future
            'end_date' => $this->faker->dateTimeBetween('+11 days', '+20 days'), // End date after start date
        ];
    }
}
