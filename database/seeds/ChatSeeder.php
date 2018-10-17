<?php

use Illuminate\Database\Seeder;

use App\User;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(\App\Chatroom::class, 20)->create()->each(function($chatroom) {
            $users = rand(1, 3);
            for($i = 0; $i < $users; $i++) {
                $user = User::orderByRaw("RAND()")->first();
                $user->chatrooms()->save($chatroom, ['admin' => rand(0, 1)]);

                factory(\App\ChatMessage::class, rand(2, 6))->create(['user_id'=> $user->id, 'chatroom_id' => $chatroom->id]);
            }

        });
    }
}
