<?php

namespace Tests\Feature\Api;

use App\Models\User;

trait UtilsTrait
{
    public function createFirstUser()
    {
        $user = User::factory()->create();
        return $user;
    }

    public function createTokenUser()
    {
        $user = $this->createFirstUser();
        $token = $user->createToken('teste')->plainTextToken;

        return $token;
    }

    public function defaultHeaders()
    {
        $token = $this->createTokenUser();

        return [
            'Authorization' => "Bearer {$token}",
        ];
    }
}