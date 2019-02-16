<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_returns_a_collection_of_categories()
    {
        $categories = factory(Category::class, 2)->create();

        $this->json('GET', '/api/categories')
            ->assertJsonFragment([
                'slug' => $categories->get(0)->slug
            ],[
                'slug' => $categories->get(1)->slug
            ]);
    }

    public function test_it_returns_only_one_parent_category()
    {
        $category = factory(Category::class)->create();

        $category->children()->save(
            factory(Category::class)->create()
        );

        $this->json('GET', '/api/categories')
            ->assertJsonCount(1, 'data');
    }

    public function test_it_is_ordered_by_their_given_order()
    {
        $category = factory(Category::class)->create([
            'order' => 2
        ]);

        $anotherCategory = factory(Category::class)->create([
            'order' => 1
        ]);

        $this->json('GET', '/api/categories')
            ->assertSeeInOrder([
                $anotherCategory->slug, $category->slug
            ]);
    }


}
