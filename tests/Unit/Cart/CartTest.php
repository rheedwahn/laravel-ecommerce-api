<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Cart\Money;
use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class CartTest extends TestCase
{
    public function test_it_can_add_product_to_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $product = factory(ProductVariation::class)->create();

        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $this->assertCount(1,$user->fresh()->cart);
    }

    public function test_it_can_increment_quantity_for_item_already_added()
    {
        $product = factory(ProductVariation::class)->create();

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $cart->add([
            ['id' => $product->id, 'quantity' => 1],
        ]);

        $cart = new Cart($user->fresh());

        $cart->add([
            ['id' => $product->id, 'quantity' => 3],
        ]);
        $this->assertEquals(4,$user->fresh()->cart->first()->pivot->quantity);
    }

    public function test_it_can_update_quantites_in_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(), [
                'quantity' => 2
            ]

        );

        $cart->update($product->id, 6);

        $this->assertEquals(6,$user->fresh()->cart->first()->pivot->quantity);
    }

    public function test_it_can_delete_a_cart_item()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(), [
                'quantity' => 2
            ]

        );

        $cart->delete($product->id);

        $this->assertCount(0,$user->fresh()->cart);
    }

    public function test_it_can_empty_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create()
        );

        $cart->empty();

        $this->assertCount(0,$user->fresh()->cart);
    }

    public function test_it_can_check_if_cart_is_empty_off_quantity()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(),[
                'quantity' => 0
            ]
        );

        $this->assertTrue($cart->isEmpty());
    }

    public function test_it_returns_a_money_instance_for_the_subtotal()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(),[
                'quantity' => 2
            ]
        );

        $this->assertInstanceOf(Money::class, $cart->subTotal());
    }

    public function test_it_can_calculate_the_subtotal()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create([
                'price' => 2000
            ]),[
                'quantity' => 2
            ]
        );

        $this->assertEquals(4000, $cart->subTotal()->amount());
    }

    public function test_it_returns_a_money_instance_for_the_total()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(),[
                'quantity' => 2
            ]
        );

        $this->assertInstanceOf(Money::class, $cart->total());
    }

    public function test_it_can_calculate_the_total()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create([
                'price' => 2000
            ]),[
                'quantity' => 2
            ]
        );

        $this->assertEquals(4000, $cart->total()->amount());
    }

    public function test_it_sync_cart_to_update_quantity()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(),[
                'quantity' => 2
            ]
        );

        $cart->syncCart();

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 0);
    }

    public function test_it_can_check_if_the_cart_has_changed()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(),[
                'quantity' => 2
            ]
        );

        $cart->syncCart();

        $this->assertTrue($cart->hasChanged());
    }
}
