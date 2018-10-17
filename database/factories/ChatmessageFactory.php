<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\ChatMessage::class, function (Faker $faker) {
    $date = null;

    return [
        'message' => $faker->text(75),
        'created_at' => $date ?: $date = $faker->dateTimeBetween($startDate = '-1 year', $endDate = 'now'),
        'updated_at' => $date
    ];
});
