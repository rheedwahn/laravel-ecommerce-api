<?php

namespace Tests\Feature\Addresses;

use App\Models\Country;
use App\Models\User;
use Tests\TestCase;

class AddressStoreTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('POST', '/api/addresses')
            ->assertStatus(401);
    }

    public function test_it_requires_a_name()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/addresses')
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_requires_a_address_1()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/addresses')
            ->assertJsonValidationErrors(['address_1']);
    }

    public function test_it_requires_a_city()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/addresses')
            ->assertJsonValidationErrors(['city']);
    }

    public function test_it_requires_a_postal_code()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/addresses')
            ->assertJsonValidationErrors(['postal_code']);
    }

    public function test_it_requires_a_postal_country()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/addresses')
            ->assertJsonValidationErrors(['country_id']);
    }

    public function test_it_requires_a_valid_country()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/addresses', [
            'country_id' => 3
        ])
            ->assertJsonValidationErrors(['country_id']);
    }

    public function test_it_has_default_field_boolean_when_supplied()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/addresses', [
            'default' => 'jhsdb'
        ])
            ->assertJsonValidationErrors(['default']);
    }

    public function test_it_stores_an_address()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/addresses', $payload = [
            'name' => $name = 'Ridwan',
            'address_1' => 'Lagos',
            'city' => 'Lagos',
            'postal_code' => 123456,
            'country_id' => factory(Country::class)->create()->id
        ]);

        $this->assertDatabaseHas('addresses', array_merge($payload, [
            'user_id' => $user->id
        ]));
    }

    public function test_it_returns_address_resource_after_creation()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/addresses', $payload = [
            'name' => $name = 'Ridwan',
            'address_1' => 'Lagos',
            'city' => 'Lagos',
            'postal_code' => 123456,
            'country_id' => factory(Country::class)->create()->id
        ])->assertJsonFragment([
            'name' => $name
        ]);
    }


}
