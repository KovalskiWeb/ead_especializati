<?php

namespace Tests\Feature\Api;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LessonTest extends TestCase
{
    use UtilsTrait, DatabaseTransactions;

    public function test_get_lessons_unauthenticated(): void
    {
        $response = $this->getJson('/modules/fake_id/lessons');

        $response->assertStatus(401);
    }

    public function test_get_lessons_of_module_not_found(): void
    {
        $response = $this->actingAs($this->createFirstUser())
                        ->getJson('/modules/fake_id/lessons');

        $response->assertStatus(200)
                    ->assertJsonCount(0, 'data');
    }

    public function test_get_lessons_module(): void
    {
        $course = Course::factory()->create();

        $response = $this->actingAs($this->createFirstUser())
                        ->getJson("/modules/{$course->id}/lessons");

        $response->assertStatus(200);
    }

    public function test_get_lessons_of_module_total(): void
    {
        $module = Module::factory()->create();
        Lesson::factory()->count(10)->create([
            'module_id' => $module->id,
        ]);

        $response = $this->actingAs($this->createFirstUser())
                        ->getJson("/modules/{$module->id}/lessons");

        $response->assertStatus(200)
                    ->assertJsonCount(10, 'data');
    }

    public function test_get_single_lesson_unauthenticated(): void
    {
        $response = $this->getJson("/lessons/fake_id");

        $response->assertStatus(401);
    }

    public function test_get_single_lesson_not_found(): void
    {
        $response = $this->actingAs($this->createFirstUser())
                        ->getJson("/lessons/fake_id");

        $response->assertStatus(404);
    }

    public function test_get_single_lesson(): void
    {
        $lesson = Lesson::factory()->create();

        $response = $this->actingAs($this->createFirstUser())
                        ->getJson("/lessons/{$lesson->id}");

        $response->assertStatus(200);
    }
}
