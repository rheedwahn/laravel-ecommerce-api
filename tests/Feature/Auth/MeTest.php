<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class MeTest extends TestCase
{
    public function test_it_returns_error_for_unauthenticated_user()
    {
        $this->json('GET', '/api/auth/me')
            ->assertStatus(401);
    }

    public function test_it_returns_user_details_when_authenticated()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'GET', '/api/auth/me')
            ->assertJsonFragment([
                'name' => $user->name
            ]);
    }
}
