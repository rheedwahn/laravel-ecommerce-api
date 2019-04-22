<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    public function test_it_validates_email_as_required()
    {
        $this->json('POST', '/api/auth/login')
                ->assertJsonValidationErrors(['email']);
    }

    public function test_it_validates_email_As_a_valid_email()
    {
        $this->json('POST', '/api/auth/login', [
            'email' => 'jhfg'
        ])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_validates_password_as_required()
    {
        $this->json('POST', '/api/auth/login')
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_can_log_in_a_user()
    {
        $user = factory(User::class)->create([
            'email' => 'ridwan@gmail.com',
            'password' => 'password'
        ]);

        $this->json('POST', '/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ])
        ->assertJsonFragment([
            'name' => $user->name
        ]);
    }

    public function test_it_returns_a_token_when_credentials_match()
    {
        $user = factory(User::class)->create([
            'email' => 'ridwan@gmail.com',
            'password' => 'password'
        ]);

        $this->json('POST', '/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ])
            ->assertJsonStructure([
                'meta' => [
                    'token'
                ]
            ]);
    }
}
