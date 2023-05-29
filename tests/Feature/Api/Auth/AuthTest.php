<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Api\UtilsTrait;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions, UtilsTrait;

    public function test_fail_auth()
    {
        // $this->withoutExceptionHandling();

        $response = $this->postJson('/auth', []);

        $response->assertStatus(422);
    }

    public function test_auth()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/auth', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'teste',
        ]);

        // $response->dump();

        $response->assertStatus(200);
    }

    public function test_error_logout()
    {
        $response = $this->postJson('/logout');

        $response->assertStatus(401);
    }

    public function test_logout()
    {
        $response = $this->postJson('/logout', [], $this->defaultHeaders());

        $response->assertStatus(200);
    }

    public function test_error_me()
    {
        $response = $this->getJson('/me');

        $response->assertStatus(401);
    }

    public function test_me()
    {
        $response = $this->getJson('/me', $this->defaultHeaders());

        $response->assertStatus(200);
    }
}
