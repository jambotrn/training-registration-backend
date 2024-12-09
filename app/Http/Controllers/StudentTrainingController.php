<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\StudentTraining;
use App\Models\TrainingSchedule;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class StudentTrainingController extends Controller
{

    public function optInOut(Request $request)
    {
        try {
            $data = $request->validate([
                'student_id' => 'required|exists:students,id',
                'schedule_id' => 'required|exists:training_schedules,id',
                'status' => 'required|in:opt-in,opt-out',
            ]);

            // Check if the record exists
            $studentTraining = StudentTraining::where('student_id', $data['student_id'])
                                              ->where('schedule_id', $data['schedule_id'])
                                              ->first();

            if ($studentTraining) {
                // Update existing record
                $studentTraining->update(['status' => $data['status']]);
            } else {
                // Create new record
                $studentTraining = StudentTraining::create($data);
            }

            return response()->json([
                'message' => 'Student training status updated successfully',
                'data' => $studentTraining,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Related resource not found', 'error' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the student training status', 'error' => $e->getMessage()], 500);
        }
    }

    public function getStudentTrainings($student_id)
    {
        try {
            $student = Student::with('trainings')->findOrFail($student_id);

            $trainings = $student->trainings->map(function ($training) {
                return [
                    'schedule_id' => $training->id,
                    'course_id' => $training->course_id,
                    'start_date' => $training->start_date,
                    'end_date' => $training->end_date,
                    'status' => $training->pivot->status,
                ];
            });

            return response()->json($trainings, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Student not found', 'error' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching student trainings', 'error' => $e->getMessage()], 500);
        }
    }
}
