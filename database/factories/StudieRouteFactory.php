<?php

use App\StudieRoute;
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

$factory->define(StudieRoute::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'key' => substr(str_random(10), 0, 10), //lengte veranderen
        'description' => $faker->paragraph(mt_rand(2, 4)),
        'due_date' => $faker->date('Y-m-d'),
    ];
});
