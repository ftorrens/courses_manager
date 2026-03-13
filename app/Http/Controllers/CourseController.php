<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'orderby' => 'sometimes|in:id,title,price,created_at',
            'direction' => 'sometimes|in:asc,desc'
        ]);

        $orderby = $validated['orderby'] ?? 'title';
        $direction = $validated['direction'] ?? 'asc';

        $courses = Course::with('instructor')
            ->orderBy($orderby, $direction)
            ->paginate(10);

        return $courses;
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $course = Course::create($request->validated());

        return response()->json($course, 201);
    }

    /**
     * Display the specified course.
     */
    public function show($id)
    {
        $course = Course::with('instructor', 'lessons')->find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        return response()->json($course);
    }

    /**
     * Update the specified course in storage.
     */
    public function update(UpdateCourseRequest $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $course->update($request->validated());

        return response()->json($course);
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $course->delete();

        return response()->json(['message' => 'Course deleted']);
    }
}
