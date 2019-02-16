<?php

namespace Tests\Unit\Models\Products;

use App\Cart\Money;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
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

}
