<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class, 1)->create(array(
            'email' => 'student@binx.nu',
            'first_name' => 'Freek',
            'last_name' => 'Tielbeeke'
        ));
        factory(\App\User::class, 1)->create(array(
            'email' => 'teacher@binx.nu',
            'first_name' => 'Thijs',
            'last_name' => 'Strohm'
        ));
        factory(\App\User::class, 1)->create(array(
            'email' => 'admin@binx.nu',
            'first_name' => 'Gido',
            'last_name' => 'Koel'
        ));

        factory(\App\User::class, 4)->create();
    }
}
