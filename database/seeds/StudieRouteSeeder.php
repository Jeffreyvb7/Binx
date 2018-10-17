<?php

use Illuminate\Database\Seeder;

class StudieRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\StudieRoute::class, 10)->create();
    }
}
