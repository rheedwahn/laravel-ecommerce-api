<?php

namespace Tests\Unit\Models\User;

use App\Models\Address;
use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_it_hashes_the_password_when_creating()
    {
        $user = factory(User::class)->create([
            'password' => 'password'
        ]);
        $this->assertNotEquals($user->password, 'password');
    }

    public function test_it_has_many_cart_items()
    {
        $user = factory(User::class)->create();
        $user->cart()->attach(
            factory(ProductVariation::class)->create()
        );
        $this->assertInstanceOf(ProductVariation::class, $user->cart->first());
    }

    public function test_it_has_a_quantity_foreach_cart_item()
    {
        $user = factory(User::class)->create();
        $user->cart()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => $quantity = 6
            ]
        );
        $this->assertEquals($user->cart->first()->pivot->quantity, 6);
    }

    public function test_it_has_many_addresses()
    {
        $user = factory(User::class)->create();
        factory(Address::class, 4)->create([
            'user_id' => $user->id
        ]);
        $this->assertEquals($user->addresses()->count(), 4);
    }
}
