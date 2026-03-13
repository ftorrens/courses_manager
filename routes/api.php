<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

// TODO: For production, move the instructors function to a separate InstructorController
// to handle instructor-related logic and follow RESTful conventions.
Route::get('/courses/instructors', [CourseController::class,'instructors']);

Route::apiResource('courses', CourseController::class);

