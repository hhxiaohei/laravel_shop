<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ProductSku::class, function (Faker $faker) {
    return [
        'title'       => $faker->text,
        'description' => $faker->sentence,
        'price'       => $faker->numberBetween(100,999),
        'stock'       => $faker->numberBetween(1,10),
    ];
});
