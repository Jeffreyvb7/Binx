<?php

use App\Task;
use App\User;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function($user) {
            for($i = 0; $i <= rand(1, 6); $i++) {
                $task = Task::orderByRaw("RAND()")->first();
                $user->submissions()->saveMany(factory(\App\Submission::class, rand(1, 3))->make(['task_id' => $task->id]));
            }
        });
    }
}
