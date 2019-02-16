<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    public function test_it_shows_collection_of_products()
    {
        $products = factory(Product::class, 2)->create();

        $this->json('GET', '/api/products')
            ->assertJsonFragment([
                'slug' => $products->get(0)->slug
            ],[
                'slug' => $products->get(1)->slug
            ]);
    }

    public function test_it_has_paginated_data()
    {
        $this->json('GET', '/api/products')
            ->assertJsonStructure([
                'data', 'links', 'meta'
            ]);
    }
}
