<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\User;
use Tests\TestCase;

class AddressIndexTest extends TestCase
{
    public function test_it_fails_if_not_authenticated()
    {
        $this->json('GET', '/api/addresses')
            ->assertStatus(401);
    }

    public function test_it_shows_addresses()
    {
        $user = factory(User::class)->create();

        $address = factory(Address::class)->create([
            'user_id' => $user->id
        ]);

        $this->jsonAs($user, 'GET', '/api/addresses')
            ->assertJsonFragment([
                'city' => $address->city
            ]);
    }

    public function test_it_can_show_county_in_addresses()
    {
        $user = factory(User::class)->create();

        $address = factory(Address::class)->create([
            'user_id' => $user->id
        ]);

        $this->jsonAs($user, 'GET', '/api/addresses')
            ->assertJsonFragment([
                'name' => $address->country->name
            ]);
    }
}
