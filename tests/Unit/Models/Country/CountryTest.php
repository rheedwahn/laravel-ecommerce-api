<?php

namespace Tests\Unit\Models\Country;

use App\Models\Country;
use App\Models\ShippingMethod;
use Tests\TestCase;

class CountryTest extends TestCase
{
    public function test_it_has_shipping_addresses()
    {
        $country = factory(Country::class)->create();

        $country->shipping_methods()->attach(factory(ShippingMethod::class)->create()->id);

        $this->assertInstanceOf(ShippingMethod::class, $country->shipping_methods->first());
    }
}
