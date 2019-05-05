<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\User;
use Tests\TestCase;

class AddressDestroyTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('DELETE', '/api/addresses/1')
            ->assertStatus(401);
    }

    public function test_it_fails_if_address_cant_be_found()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'DELETE', '/api/addresses/1')
            ->assertStatus(404);

    }

    public function test_it_deletes_address_from_list()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create([
            'user_id' => $user->id
        ]);
        $address2 = factory(Address::class)->create([
            'user_id' => $user->id
        ]);
        $this->jsonAs($user, 'DELETE', '/api/addresses/'.$address->id);
        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id
        ]);
        $this->assertDatabaseHas('addresses', [
            'id' => $address2->id
        ]);
    }
}
