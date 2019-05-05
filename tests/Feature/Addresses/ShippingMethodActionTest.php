<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;

class ShippingMethodActionTest extends TestCase
{
    public function test_it_fails_if_not_authenticated()
    {
        $this->json('GET', '/api/addresses/3/shipping')
            ->assertStatus(401);
    }

    public function test_it_fails_if_address_cant_be_found()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'GET', '/api/addresses/3/shipping')
            ->assertStatus(404);
    }

    public function test_it_fails_if_a_user_doesnt_own_the_address()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create();

        $this->jsonAs($user, 'GET', '/api/addresses/'.$address->id.'/shipping')
            ->assertStatus(403);
    }

    public function test_it_shows_shipping_information_for_address()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create([
            'user_id' => $user->id
        ]);
        $address->country->shipping_methods()->attach(
            factory(ShippingMethod::class)->create()->id
        );
        $this->jsonAs($user, 'GET', '/api/addresses/'.$address->id.'/shipping')
            ->assertJsonFragment([
                'name' => $address->country->shipping_methods->first()->name
            ]);
    }
}
