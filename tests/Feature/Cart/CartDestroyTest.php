<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class CartDestroyTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('DELETE', '/api/carts/1')
            ->assertStatus(401);
    }

    public function test_it_fails_if_product_cant_be_found()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'PATCH', '/api/carts/1')
            ->assertStatus(404);
    }

    public function test_it_can_delete_a_product_from_the_cart()
    {
        $user = factory(User::class)->create();
        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(), [
                'quantity' => 2
            ]
        );
        $this->jsonAs($user, 'DELETE', '/api/carts/'.$product->id);

        $this->assertDatabaseMissing('cart_user', [
            'product_variation_id' => $product->id
        ]);
    }
}
