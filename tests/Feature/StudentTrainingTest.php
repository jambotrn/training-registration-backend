<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Student;
use App\Models\TrainingSchedule;
use App\Models\StudentTraining;
use App\Models\User;

class StudentTrainingTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and generate a JWT token
        $user = User::factory()->create();
        $this->token = auth()->login($user);
    }

    public function test_student_can_opt_in_to_training()
    {
        $student = Student::factory()->create();
        $schedule = TrainingSchedule::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/trainings/opt-in-out', [
            'student_id' => $student->id,
            'schedule_id' => $schedule->id,
            'status' => 'opt-in',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'opt-in']);
    }

    public function test_can_get_all_student_trainings()
    {
        $student = Student::factory()->create();
        $schedule = TrainingSchedule::factory()->create();
        StudentTraining::create([
            'student_id' => $student->id,
            'schedule_id' => $schedule->id,
            'status' => 'opt-in',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/students/{$student->id}/trainings");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['schedule_id', 'course_id', 'start_date', 'end_date', 'status']
                 ]);
    }
}

