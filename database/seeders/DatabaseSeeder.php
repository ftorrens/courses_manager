<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Video;
use App\Models\Comment;
use App\Models\Rating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // users
        $users = User::factory(10)->create();

        // instructors with courses
        $instructors = Instructor::factory(5)
            ->create()
            ->each(function ($instructor) use ($users) {

                $courses = Course::factory(3)
                    ->create([
                        'instructor_id' => $instructor->id
                    ]);

                $courses->each(function ($course) use ($users) {

                    // lessons
                    $lessons = Lesson::factory(4)
                        ->create([
                            'course_id' => $course->id
                        ]);

                    // video per lesson
                    $lessons->each(function ($lesson) {
                        Video::factory()->create([
                            'lesson_id' => $lesson->id
                        ]);
                    });

                    // favorites
                    $course->favorites()->attach(
                        $users->random(3)->pluck('id')
                    );

                    // comments
                    Comment::factory(3)->create([
                        'commentable_id' => $course->id,
                        'commentable_type' => Course::class
                    ]);

                    // ratings
                    Rating::factory(3)->create([
                        'rateable_id' => $course->id,
                        'rateable_type' => Course::class
                    ]);

                });

                // comments for instructor
                Comment::factory(2)->create([
                    'commentable_id' => $instructor->id,
                    'commentable_type' => Instructor::class
                ]);

                // ratings for instructor
                Rating::factory(2)->create([
                    'rateable_id' => $instructor->id,
                    'rateable_type' => Instructor::class
                ]);

            });
    }
}
