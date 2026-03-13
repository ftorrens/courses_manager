<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'orderby' => 'sometimes|in:id,title,price,created_at',
            'direction' => 'sometimes|in:asc,desc',
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:0',
        ]);

        $orderby = $validated['orderby'] ?? 'title';
        $direction = $validated['direction'] ?? 'asc';

        $query = Course::withCount('lessons')
            ->withAvg('ratings', 'score');

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $courses = $query
            ->orderBy($orderby, $direction)
            ->paginate(10);

        return CourseResource::collection($courses);
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $course = Course::create($request->validated());

        return (new CourseResource($course))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->loadCount('lessons')
               ->loadAvg('ratings', 'score');

        return new CourseResource($course);
    }

    /**
     * Update the specified course in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update($request->validated());

        return new CourseResource($course);
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return response()->json([
            'message' => 'Course deleted'
        ]);
    }
}