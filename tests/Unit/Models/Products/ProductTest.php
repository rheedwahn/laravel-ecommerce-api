<?php

namespace Tests\Unit\Products;

use App\Cart\Money;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Stock;
use Tests\TestCase;


class ProductTest extends TestCase
{
    public function test_it_uses_the_slug_for_route_key_name()
    {
        $product = new Product();

        $this->assertEquals($product->getRouteKeyName(), 'slug');
    }

    public function test_it_has_many_categories()
    {
        $product = factory(Product::class)->create();

        $product->categories()->save(
            factory(Category::class)->create()
        );

        $this->assertInstanceOf(Category::class, $product->categories->first());
    }

    public function test_product_should_have_many_variations()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            factory(ProductVariation::class)->create([
                'product_id' => $product->id
            ])
        );

        $this->assertInstanceOf(ProductVariation::class, $product->variations->first());
    }

    public function test_it_returns_money_instance_for_that_price()
    {
        $product = factory(Product::class)->create();

        $this->assertInstanceOf(Money::class, $product->price);
    }

    public function test_it_returns_a_formatted_price()
    {
        $product = factory(Product::class)->create([
            'price' => 2000
        ]);

        $this->assertEquals($product->formattedPrice, '₦20.00');
    }

    public function test_it_can_count_number_of_product_in_stock()
    {
        $product = factory(Product::class)->create();
        $product_variation = factory(ProductVariation::class)->create([
            'product_id' => $product->id
        ]);
        $product_variation->stocks()->save(
            factory(Stock::class)->create([
                'quantity' => $quantity = 5
            ])
        );

        $this->assertEquals($product->stockCount(), $quantity);
    }

    public function test_it_can_check_if_a_product_is_in_stock()
    {
        $product = factory(Product::class)->create();
        $product_variation = factory(ProductVariation::class)->create([
            'product_id' => $product->id
        ]);
        $product_variation->stocks()->save(
            factory(Stock::class)->create([
                'quantity' => $quantity = 5
            ])
        );

        $this->assertTrue($product->inStock());
    }
}
