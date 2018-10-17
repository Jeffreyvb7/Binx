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

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    $data = [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'birthdate' =>$faker->date('Y-m-d'),
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'socket_auth_token' => bcrypt(str_random(10)),
        'phonenr' => $faker->e164PhoneNumber,
        'remember_token' => str_random(10),
    ];

    $data['email'] = strtolower($data['first_name']) . "@binx.nu";

    return $data;
});
