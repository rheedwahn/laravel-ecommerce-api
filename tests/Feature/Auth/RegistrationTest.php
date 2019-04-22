<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    public function test_it_requires_a_name()
    {
        $this->json('POST', '/api/auth/register')
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_requires_an_email()
    {
        $this->json('POST', '/api/auth/register')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_valid_email()
    {
        $this->json('POST', '/api/auth/register', [
            'email' => 'nope'
        ])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_password()
    {
        $this->json('POST', '/api/auth/register')
            ->assertJsonValidationErrors(['password']);
    }

    public function test_email_address_is_unique()
    {
        $user = factory(User::class)->create();
        $this->json('POST', '/api/auth/register', [
            'email' => $user->email
        ])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_password_with_min_length_six()
    {
        $this->json('POST', '/api/auth/register', [
            'password' => 'zrdf'
        ])
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_registers_a_user()
    {
        $this->json('POST', '/api/auth/register', [
            'name' => $name = 'ridwan busari',
            'email' => $email = 'ridwan@gmail.com',
            'password' => 'secret'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email
        ]);
    }

    public function test_it_returns_a_user_after_registration()
    {
        $this->json('POST', '/api/auth/register', [
            'name' => 'ridwan busari',
            'email' => $email = 'ridwan@gmail.com',
            'password' => 'secret'
        ])->assertJsonFragment([
            'email' => $email
        ]);
    }


}
