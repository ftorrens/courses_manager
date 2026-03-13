<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Instructor;
use App\Services\CourseRatingService;

class CourseController extends Controller
{
    /**
     * Return a listing of the courses.
     */
    public function index(Request $request, CourseRatingService $ratingService)
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

        // Add rating_avg to each course
        $courses->getCollection()->transform(function($course) use ($ratingService) {
            $course->rating_avg = $ratingService->getAverage($course);
            return $course;
        });

        return CourseResource::collection($courses);
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(StoreCourseRequest $request, CourseRatingService $ratingService)
    {
        $course = Course::create($request->validated());

        $course->rating_avg = $ratingService->getAverage($course);

        return (new CourseResource($course))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course, CourseRatingService $ratingService)
    {
        $course->load('lessons');
        $course->rating_avg = $ratingService->getAverage($course);

        $resource = new CourseResource($course);

        return $resource;
    }

    /**
     * Update the specified course in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course, CourseRatingService $ratingService)
    {
        $course->update($request->validated());
        $course->rating_avg = $ratingService->getAverage($course);

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

    /**
     * Return a listing of the instructors.
     */
    // TODO: For production, move this function to its own controller, e.g., InstructorController.
    // This will separate instructor-related logic from courses and follow RESTful conventions.
    public function instructors()
    {
        $instructors = Instructor::query()
            ->select(['id','name'])
            ->orderBy('id')
            ->paginate(50);

        return response()->json($instructors);
    }
}