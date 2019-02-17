<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Stock::class, function (Faker $faker) {
    return [
        'quantity' => $faker->numberBetween(1, 1000),
        'product_variation_id' => factory(\App\Models\ProductVariation::class)->create()->id
    ];
});
