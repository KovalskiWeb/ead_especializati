<?php

namespace Tests\Feature\Api;

use App\Models\Lesson;
use App\Models\Support;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SupportTest extends TestCase
{
    use UtilsTrait;

    public function test_my_supports_unauthorized(): void
    {
        $response = $this->getJson('/my-supports');

        $response->assertStatus(401);
    }

    public function test_my_supports(): void
    {
        $user = $this->createFirstUser();
        $token = $user->createToken('teste')->plainTextToken;

        Support::factory()->count(10)->create([
            'user_id' => $user->id,
        ]);
        Support::factory()->count(10)->create();

        $response = $this->getJson('/my-supports', [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200)
                    ->assertJsonCount(10, 'data');
    }

    public function test_get_supports_unauthorized(): void
    {
        $response = $this->getJson('/supports');

        $response->assertStatus(401);
    }

    public function test_get_supports(): void
    {
        Support::factory()->count(10)->create();

        $response = $this->actingAs($this->createFirstUser())
                            ->getJson('/supports');

        $response->assertStatus(200)
                    ->assertJsonCount(10, 'data');
    }

    public function test_get_supports_filter_lesson(): void
    {
        $lesson = Lesson::factory()->create();

        Support::factory()->count(10)->create();
        Support::factory()->count(10)->create([
            'lesson_id' => $lesson->id,
            'status' => 'F',
            'description' => 'Wesley Kovalski Pereira',
        ]);

        $payload = [
            'lesson' => $lesson->id,
            'status' => 'F',
            'filter' => 'Kovalski',
        ];

        $response = $this->json('GET', '/supports', $payload, $this->defaultHeaders());

        $response->assertStatus(200)
                    ->assertJsonCount(10, 'data');
    }

    public function test_store_support_unauthorized(): void
    {
        $response = $this->postJson('/supports');

        $response->assertStatus(401);
    }

    public function test_store_support_error_validations(): void
    {
        $response = $this->actingAs($this->createFirstUser())
                            ->postJson('/supports', []);

        $response->assertStatus(422);
    }

    public function test_store_support(): void
    {
        $lesson = Lesson::factory()->create();
        $payload = [
            'lesson' => $lesson->id,
            'status' => 'P',
            'description' => 'Description test',
        ];

        $response = $this->actingAs($this->createFirstUser())
                            ->postJson('/supports', $payload);

        $response->assertStatus(201);
    }
}
