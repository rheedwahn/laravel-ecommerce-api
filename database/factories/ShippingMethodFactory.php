<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\ShippingMethod::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->numberBetween(1000000, 1000000000)
    ];
});
