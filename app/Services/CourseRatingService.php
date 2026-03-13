<?php

namespace App\Services;

use App\Models\Course;

class CourseRatingService
{
    /**
     * Get the average rating of a course
     */
    public function getAverage(Course $course): float
    {
        return (float) $course->ratings()->avg('score') ?? 0;
    }
}