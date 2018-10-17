<?php

use Faker\Generator as Faker;

$factory->define(App\Portfolio::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->realText(300)
    ];
});
