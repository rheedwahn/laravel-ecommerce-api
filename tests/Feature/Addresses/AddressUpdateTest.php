<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\User;
use Tests\TestCase;

class AddressUpdateTest extends TestCase
{
    public function test_it_fails_if_not_authenticated()
    {
        $this->json('GET', '/api/addresses')
            ->assertStatus(401);
    }

    public function test_it_fails_if_address_does_not_exist()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user,'PATCH', '/api/addresses/3')
            ->assertStatus(404);
    }

    public function test_it_switches_address_to_default()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create([
            'user_id' => $user->id,
            'default' => false
        ]);
        $this->jsonAs($user,'PATCH', '/api/addresses/'.$address->id);
        $this->assertDatabaseHas('addresses', [
                'id' => $address->id,
                'default' => true
            ]);
    }

    public function test_it_switches_address_to_default_and_others_to_false_after_updating()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create([
            'user_id' => $user->id,
            'default' => true
        ]);
        $address_2 = factory(Address::class)->create([
            'user_id' => $user->id,
            'default' => false
        ]);
        $this->jsonAs($user,'PATCH', '/api/addresses/'.$address_2->id);
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'id' => $address->id,
            'default' => false
        ]);
    }
}
