<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class CartStoreTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('POST', '/api/carts')
            ->assertStatus(401);
    }

    public function test_it_requires_a_products()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/carts')
            ->assertJsonValidationErrors(['products']);
    }

    public function test_it_requires_product_to_be_an_array()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/carts', [
            'product' => 1
        ])
            ->assertJsonValidationErrors(['products']);
    }

    public function test_it_requires_product_to_have_an_id()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/carts', [
            'products' => [
                ['quantity' => 1]
            ]
        ])
            ->assertJsonValidationErrors(['products.0.id']);
    }

    public function test_it_requires_product_to_be_in_existence()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/carts', [
            'products' => [
                ['id' => 1, 'quantity' => 1]
            ]
        ])
            ->assertJsonValidationErrors(['products.0.id']);
    }

    public function test_it_has_quantity_to_be_numeric()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/carts', [
            'products' => [
                ['id' => 1, 'quantity' => 'r']
            ]
        ])
            ->assertJsonValidationErrors(['products.0.quantity']);
    }

    public function test_it_has_quantity_to_be_at_least_one()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', '/api/carts', [
            'products' => [
                ['id' => 1, 'quantity' => 0]
            ]
        ])
            ->assertJsonValidationErrors(['products.0.quantity']);
    }

    public function test_it_can_add_product_to_cart()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $this->jsonAs($user, 'POST', '/api/carts', [
            'products' => [
                ['id' => $product->id, 'quantity' => 3]
            ]
        ]);

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' => 3
        ]);
    }
}
