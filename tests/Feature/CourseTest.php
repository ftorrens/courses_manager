<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /* Check that:
    - The expected fields are returned.
    - Pagination is applied correctly.
    */
    #[Test]
    public function it_returns_paginated_courses()
    {
        $instructor = Instructor::factory()->create();
        Course::factory()->count(15)->for($instructor)->create();

        $response = $this->getJson('/api/courses');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id','title','description','price','instructor','lessons_count','rating_avg','created_at']
                     ],
                     'links',
                     'meta'
                 ]);

        $this->assertCount(10, $response->json('data')); // pagination 10
    }

    /* Check that:
    - Returns the correct course
    - Includes the instructor
    - The resource structure is respected
    */
    #[Test]
    public function it_returns_a_single_course_with_instructor_and_rating()
    {
        $instructor = Instructor::factory()->create();
        $course = Course::factory()->for($instructor)->create();

        $response = $this->getJson("/api/courses/{$course->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id' => $course->id,
                        'title' => $course->title,
                        'instructor' => [
                            'id' => $instructor->id,
                            'name' => $instructor->name
                        ]
                    ]
                ]);
    }

    /* Check that:
    - That the course is created correctly.
    - That the database contains the record.
    */
    #[Test]
    public function it_can_create_a_course()
    {
        $instructor = Instructor::factory()->create();
        $payload = [
            'title' => 'New course',
            'description' => 'Course description',
            'price' => 49.99,
            'instructor_id' => $instructor->id
        ];

        $response = $this->postJson('/api/courses', $payload);

        $response->assertStatus(201)
                ->assertJson([
                    'data' => [
                        'title' => 'New course',
                        'description' => 'Course description',
                        'price' => '49.99'
                    ]
                ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'New course',
            'instructor_id' => $instructor->id
        ]);
    }

    /* Check that:
    - Resource update and response.
    */
    #[Test]
    public function it_can_update_a_course()
    {
        $course = Course::factory()->create(['title' => 'Old title']);

        $payload = ['title' => 'Updated title'];

        $response = $this->putJson("/api/courses/{$course->id}", $payload);

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'title' => 'Updated title'
                    ]
                ]);

        $this->assertDatabaseHas('courses', ['id' => $course->id, 'title' => 'Updated title']);
    }

    /* Check that:
    - The API returned a 200 and correct message.
    - The course was indeed deleted.
    */
    #[Test]
    public function it_can_delete_a_course()
    {
        $course = Course::factory()->create();

        $response = $this->deleteJson("/api/courses/{$course->id}");

        $response->assertStatus(200)
                ->assertJson(['message' => 'Course deleted']);

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }
}