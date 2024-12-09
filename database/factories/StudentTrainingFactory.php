<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StudentTraining;
use App\Models\Student;
use App\Models\TrainingSchedule;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentTraining>
 */
class StudentTrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'schedule_id' => TrainingSchedule::factory(),
            'status' => $this->faker->randomElement(['opt-in', 'opt-out']),
        ];
    }
}
