<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CourseController extends Controller
{
    public function index()
    {
        try {
            $courses = Course::all();
            return response()->json($courses, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching courses', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'duration' => 'required|integer',
            ]);

            $course = Course::create($data);

            return response()->json($course, 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while creating the course', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
       try {
            $course = Course::findOrFail($id); 
            return response()->json($course, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Course not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the course', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            $data = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'duration' => 'sometimes|integer',
            ]);

            $course->update($data);

            return response()->json($course, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Course not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the course', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();
            return response()->json(['message' => 'Course deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Course not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the course', 'error' => $e->getMessage()], 500);
        }
    }
}
