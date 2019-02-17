<?php

namespace Tests\Unit\Models\Products;

use App\Cart\Money;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use App\Models\Stock;
use Tests\TestCase;

class ProductVariationTest extends TestCase
{
    public function test_it_has_one_product_variation_type()
    {
        $product_variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(ProductVariationType::class, $product_variation->type);
    }

    public function test_it_belongs_to_a_product()
    {
        $product_variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(Product::class, $product_variation->product);
    }

    public function test_it_returns_money_instance_for_the_class()
    {
        $product_variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(Money::class, $product_variation->price);
    }

    public function test_it_returns_a_formatted_price()
    {
        $product_variation = factory(ProductVariation::class)->create([
            'price' => 2000
        ]);
        $this->assertEquals($product_variation->formattedPrice, '₦20.00');
    }

    public function test_it_returns_product_price_if_variation_price_is_missing()
    {
        $product = factory(Product::class)->create();
        $product->variations()->save(
            $product_variation = factory(ProductVariation::class)->create([
                                    'price' => null
                                ])
        );

        $this->assertEquals($product->price->amount(), $product_variation->price->amount());
    }

    public function test_it_can_check_if_variation_price_differes_from_product()
    {
        $product = factory(Product::class)->create([
            'price' => 5000
        ]);

        $product_variation = factory(ProductVariation::class)->create([
            'price' => 3000,
            'product_id' => $product->id
        ]);

        $this->assertTrue($product_variation->priceVaries());
    }

    public function test_it_has_many_stocks()
    {
        $product_variation = factory(ProductVariation::class)->create();
        $product_variation->stocks()->save(
            factory(Stock::class)->create([
                'product_variation_id' => $product_variation->id
            ])
        );
        $this->assertInstanceOf(Stock::class, $product_variation->stocks->first());
    }

    public function test_it_has_stock_information()
    {
        $product_variation = factory(ProductVariation::class)->create();
        $product_variation->stocks()->save(
            factory(Stock::class)->create([
                'product_variation_id' => $product_variation->id
            ])
        );
        $this->assertInstanceOf(ProductVariation::class, $product_variation->stock->first());
    }

    public function test_it_has_stock_count_pivot_within_stock_information()
    {
        $product_variation = factory(ProductVariation::class)->create();
        $product_variation->stocks()->save(
            factory(Stock::class)->create([
                'product_variation_id' => $product_variation->id,
                'quantity' => $quantity = 5
            ])
        );

        $this->assertEquals($product_variation->stock->sum('pivot.stock'), $quantity);
    }

    public function test_it_has_in_stock_within_stock_information()
    {
        $product_variation = factory(ProductVariation::class)->create();
        $product_variation->stocks()->save(
            factory(Stock::class)->create()
        );

        $this->assertTrue($product_variation->stock->first()->pivot->in_stock);
    }

    public function test_if_it_is_in_stock()
    {
        $product_variation = factory(ProductVariation::class)->create();
        $product_variation->stocks()->save(
            factory(Stock::class)->create()
        );

        $this->assertTrue($product_variation->inStock());
    }

    public function test_it_can_check_the_stock_number()
    {
        $product_variation = factory(ProductVariation::class)->create();
        $product_variation->stocks()->save(
            factory(Stock::class)->create([
                'quantity' => $quantity = 5
            ])
        );

        $this->assertEquals($product_variation->stockCount(), $quantity);
    }

}
