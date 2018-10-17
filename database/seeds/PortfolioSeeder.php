<?php

use App\User;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function($user) {
            factory(\App\Portfolio::class, rand(1, 3))->create(['user_id' => $user->id])->each(function($portfolio) use ($user) {
                for ($i = 0; $i <= rand(3, 5); $i++) {
                    $submission = $user->submissions()->orderByRaw('RAND()')->first();
                    if(!$portfolio->submissions->contains($submission->id)) $portfolio->submissions()->save($submission);
                }
            });
        });
    }
}
