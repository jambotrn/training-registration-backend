<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingSchedule;
use App\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class TrainingScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $schedules = TrainingSchedule::with('course')->get();
            return response()->json($schedules, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching training schedules', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'course_id' => 'required|exists:courses,id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
            ]);

            $schedule = TrainingSchedule::create($data);

            return response()->json($schedule, 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while creating the training schedule', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $schedule = TrainingSchedule::with('course')->findOrFail($id);
            return response()->json($schedule, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Training schedule not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the training schedule', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $schedule = TrainingSchedule::findOrFail($id);

            $data = $request->validate([
                'course_id' => 'sometimes|exists:courses,id',
                'start_date' => 'sometimes|date|after_or_equal:today',
                'end_date' => 'sometimes|date|after:start_date',
            ]);

            $schedule->update($data);

            return response()->json($schedule, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Training schedule not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the training schedule', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $schedule = TrainingSchedule::findOrFail($id);
            $schedule->delete();
            return response()->json(['message' => 'Training schedule deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Training schedule not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the training schedule', 'error' => $e->getMessage()], 500);
        }
    }
    
}
