<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class CartIndexTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('GET', '/api/carts')
            ->assertStatus(401);
    }

    public function test_it_shows_products_in_user_cart()
    {
        $user = factory(User::class)->create();
        $user->cart()->sync(
            $product = factory(ProductVariation::class)->create()
        );
        $this->jsonAs($user, 'GET', '/api/carts')
            ->assertJsonFragment([
                'id' => $product->id
            ]);
    }

    public function test_it_shows_products_if_cart_is_empty()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'GET', '/api/carts')
            ->assertJsonFragment([
                'empty' => true
            ]);
    }

    public function test_it_shows_a_formatted_subtotal()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'GET', '/api/carts')
            ->assertJsonFragment([
                'sub_total' => '₦0.00'
            ]);
    }

    public function test_it_shows_a_formatted_total()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'GET', '/api/carts')
            ->assertJsonFragment([
                'total' => '₦0.00'
            ]);
    }

    public function test_it_shows_if_the_cart_changes()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'GET', '/api/carts')
            ->assertJsonFragment([
                'changed' => false
            ]);
    }
}
