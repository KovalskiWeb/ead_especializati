<?php

namespace Tests\Feature\Api;

use App\Models\Support;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReplySupportTest extends TestCase
{
    use UtilsTrait;

    public function test_store_reply_to_support_unauthorized(): void
    {
        $response = $this->postJson('/replies');

        $response->assertStatus(401);
    }

    public function test_store_reply_to_support_error_validations(): void
    {
        $response = $this->actingAs($this->createFirstUser())
                            ->postJson('/replies', []);

        $response->assertStatus(422);
    }

    public function test_store_reply_to_support(): void
    {
        $support = Support::factory()->create();
        $payload = [
            'support' => $support->id,
            'description' => 'test description reply support',
        ];

        $response = $this->actingAs($this->createFirstUser())
                            ->postJson('/replies', $payload);

        $response->assertStatus(201);
    }
}
