<?php

namespace Tests\Feature\Api;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use UtilsTrait, DatabaseTransactions;

    public function test_unauthenticated_courses(): void
    {
        $response = $this->getJson('/courses');

        $response->assertStatus(401);
    }

    public function test_get_all_courses(): void
    {
        $response = $this->getJson('/courses', $this->defaultHeaders());

        $response->assertStatus(200);
    }

    public function test_get_all_courses_total(): void
    {
        $courses = Course::factory()->count(10)->create();

        $response = $this->getJson('/courses', $this->defaultHeaders());

        $response->assertStatus(200)
                    ->assertJsonCount(count($courses), 'data');
    }

    public function test__get_single_course_unauthenticated(): void
    {
        $response = $this->getJson('/courses/fake_id');

        $response->assertStatus(401);
    }

    public function test__get_single_course_not_found(): void
    {
        $response = $this->getJson('/courses/fake_id', $this->defaultHeaders());

        $response->assertStatus(404);
    }

    public function test__get_single_course(): void
    {
        $course = Course::factory()->create();

        $response = $this->actingAs($this->createFirstUser())
                        ->getJson("/courses/{$course->id}");

        $response->assertStatus(200);
    }
}
