<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class CartUpdateTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('PATCH', '/api/carts/1')
            ->assertStatus(401);
    }

    public function test_it_fails_if_product_cant_be_found()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'PATCH', '/api/carts/1')
            ->assertStatus(404);
    }

    public function test_it_requires_a_quantity()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $this->jsonAs($user, 'PATCH', '/api/carts/'.$product->id)
            ->assertJsonValidationErrors(['quantity']);
    }

    public function test_it_requires_a_numeric_quantity()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $this->jsonAs($user, 'PATCH', '/api/carts/'.$product->id, [
            'quantity' => 'dbf'
        ])
            ->assertJsonValidationErrors(['quantity']);
    }

    public function test_it_requires_a_quantity_of_at_least_one()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $this->jsonAs($user, 'PATCH', '/api/carts/'.$product->id, [
            'quantity' => 0
        ])
            ->assertJsonValidationErrors(['quantity']);
    }

    public function test_it_updates_the_quantity()
    {
        $user = factory(User::class)->create();
        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(), [
                'quantity' => 2
            ]
        );
        $this->jsonAs($user, 'PATCH', '/api/carts/'.$product->id, [
            'quantity' => $quantity =  5
        ]);

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' => $quantity,
            'user_id' => $user->id
        ]);
    }
}
