<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

Route::apiResource('courses', CourseController::class);

