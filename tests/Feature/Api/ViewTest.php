<?php

namespace Tests\Feature\Api;

use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ViewTest extends TestCase
{
    use UtilsTrait, DatabaseTransactions;

    public function test_make_viewed_unauthorized(): void
    {
        $response = $this->postJson('/lessons/viewed');

        $response->assertStatus(401);
    }

    public function test_make_viewed_not_found(): void
    {
        $payload = [];

        $response = $this->actingAs($this->createFirstUser())
                            ->postJson('/lessons/viewed', $payload);

        $response->assertStatus(422);
    }

    public function test_make_viewed_invalid_lesson(): void
    {
        $payload = [
            'lesson' => 'fake_lesson',
        ];

        $response = $this->actingAs($this->createFirstUser())
                            ->postJson('/lessons/viewed', $payload);

        $response->assertStatus(422);
    }

    public function test_make_viewed(): void
    {
        $lesson = Lesson::factory()->create();
        $payload = [
            'lesson' => $lesson->id,
        ];

        $response = $this->actingAs($this->createFirstUser())
                            ->postJson('/lessons/viewed', $payload);

        $response->assertStatus(200);
    }
}
