<?php

use Illuminate\Database\Seeder;

class InboxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (App\User::all() as $user) {
            // each user have several conversation
            $conversation = App\Conversation::create(['can_reply' => rand(0, 1)]);
            // a conversation
            $user->conversations()->save($conversation);
            // a conversation can have several user
            $bound_k = rand(1, 4);
            $visited = [$user->id];
            for ($k = 1; $k <= $bound_k; $k++) {
                $user_id = rand(1, 50);
                if (!in_array($user_id, $visited)) {
                    $conversation->users()->attach($k);

                    $bound_j = rand(1, 3);
                    // a conversation can have many message
                    for($j = 1; $j < $bound_j; $j++) {
                        $message = factory(App\Message::class)->create();
                        // a message is sent by a user
                        App\User::find($k)->sentMessages()->save($message);
                        $conversation->messages()->save($message);
                    }

                    array_push($visited, $user_id);
                }
            }
        }
    }
}
