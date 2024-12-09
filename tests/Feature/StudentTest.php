<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentControllerTest extends TestCase
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

    public function test_can_get_students()
    {
        // Arrange: Create some students
        $students = Student::factory()->count(3)->create();

        // Act: Send a GET request to the /students endpoint
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/students');

        // Assert: The response is OK and contains the correct number of students
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /**
     * Test to create a new student.
     */
    public function test_can_create_student()
    {
        // Arrange: Data to create a new student
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ];

        // Act: Send a POST request to store the student
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/students', $data);

        // Assert: The response is created and contains the student data
        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ]);

        // Check if the student is in the database
        $this->assertDatabaseHas('students', $data);
    }

    /**
     * Test to show a specific student.
     */
    public function test_can_show_student()
    {
        // Arrange: Create a student
        $student = Student::factory()->create();

        // Act: Send a GET request to show the student
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/students/{$student->id}");

        // Assert: The response is OK and contains the student's data
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'phone' => $student->phone,
        ]);
    }

    /**
     * Test to handle student not found when showing a student.
     */
    public function test_cannot_show_nonexistent_student()
    {
        // Act: Send a GET request to show a student with a non-existent ID
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/students/999999");

        // Assert: The response is 404 and contains the correct error message
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Student not found']);
    }

    /**
     * Test to update a student's data.
     */
    public function test_can_update_student()
    {
        // Arrange: Create a student
        $student = Student::factory()->create();

        // Data to update
        $updatedData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0987654321',
        ];

        // Act: Send a PUT request to update the student
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/students/{$student->id}", $updatedData);

        // Assert: The response is OK and the student's data is updated
        $response->assertStatus(200);
        $response->assertJson($updatedData);

        // Check if the student data was updated in the database
        $this->assertDatabaseHas('students', $updatedData);
    }

    /**
     * Test to handle student not found when updating a student.
     */
    public function test_cannot_update_nonexistent_student()
    {
        // Data to update
        $updatedData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0987654321',
        ];

        // Act: Send a PUT request to update a student with a non-existent ID
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/students/999999", $updatedData);

        // Assert: The response is 404 and contains the correct error message
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Student not found']);
    }

    /**
     * Test to delete a student.
     */
    public function test_can_delete_student()
    {
        // Arrange: Create a student
        $student = Student::factory()->create();

        // Act: Send a DELETE request to remove the student
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/students/{$student->id}");

        // Assert: The response is OK and the student is deleted
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Student deleted successfully']);

        // Check if the student has been deleted from the database
        $this->assertDeleted($student);
    }

    /**
     * Test to handle student not found when deleting a student.
     */
    public function test_cannot_delete_nonexistent_student()
    {
        // Act: Send a DELETE request to delete a student with a non-existent ID
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/students/999999");

        // Assert: The response is 404 and contains the correct error message
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Student not found']);
    }
}
