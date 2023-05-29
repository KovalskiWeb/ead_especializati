<?php

namespace Tests\Feature\Api;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ModuleTest extends TestCase
{
    use UtilsTrait, DatabaseTransactions;

    public function test_get_modules_unauthenticated(): void
    {
        $response = $this->getJson('/courses/fake_id/modules');

        $response->assertStatus(401);
    }

    public function test_get_modules_course_not_found(): void
    {
        $response = $this->actingAs($this->createFirstUser())
                        ->getJson('/courses/fake_id/modules');

        $response->assertStatus(200)
                    ->assertJsonCount(0, 'data');
    }

    public function test_get_modules_course(): void
    {
        $course = Course::factory()->create();

        $response = $this->actingAs($this->createFirstUser())
                        ->getJson("/courses/{$course->id}/modules");

        $response->assertStatus(200);
    }

    public function test_get_modules_course_total(): void
    {
        $course = Course::factory()->create();
        Module::factory()->count(10)->create([
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($this->createFirstUser())
                        ->getJson("/courses/{$course->id}/modules");

        $response->assertStatus(200)
                    ->assertJsonCount(10, 'data');
    }
}
