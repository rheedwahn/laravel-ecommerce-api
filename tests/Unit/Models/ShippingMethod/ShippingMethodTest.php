<?php

namespace Tests\Unit\Models\ShippingMethod;

use App\Cart\Money;
use App\Models\Country;
use App\Models\ShippingMethod;
use Tests\TestCase;

class ShippingMethodTest extends TestCase
{
    public function test_it_returns_money_instance_for_the_price()
    {
        $shipping_method = factory(ShippingMethod::class)->create();

        $this->assertInstanceOf(Money::class, $shipping_method->price);
    }

    public function test_it_returns_a_formatted_price()
    {
        $shipping_method = factory(ShippingMethod::class)->create([
            'price' => 3000
        ]);

        $this->assertEquals($shipping_method->formattedPrice, '₦30.00');
    }

    public function test_it_has_countries()
    {
        $shipping_method = factory(ShippingMethod::class)->create();

        $shipping_method->countries()->attach(factory(Country::class)->create()->id);

        $this->assertInstanceOf(Country::class, $shipping_method->countries->first());
    }
}
