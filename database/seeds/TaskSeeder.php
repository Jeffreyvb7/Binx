<?php

use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\StudieRoute::all()->each(function($studieRoute) {
            $studieRoute->tasks()->saveMany(
                factory(\App\Task::class, mt_rand(0, 20))->make()
            );
        });

    }
}
