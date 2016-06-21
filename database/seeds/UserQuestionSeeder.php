<?php

use Illuminate\Database\Seeder;

class UserQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create 50 users
        factory(App\User::class, 50)->create();

        // each user post several question
        foreach (App\User::all() as $user) {
            // a user can post many question
            $bound_i = rand(5, 10);
            for($i = 0; $i < $bound_i; $i++) {
                $question = factory(App\Question::class)->make();
                $question->save();

                // an question is posted by user
                $user->questions()->save($question);


                // question is categorized by many topics
                $bound_j = rand(1, 8);
                for($j = 0; $j < $bound_j; $j++) {
                    $question->topics()->save(App\Topic::all()->random(1));
                }

                // question have many answers
                $bound_j = rand(6, 12);
                for($j = 0; $j < $bound_j; $j++) {
                    $answer = factory(App\Answer::class)->make();

                    // an answer belongs to a user
                    App\User::all()->random(1)->answers()->save($answer);

                    // an answer have many replies
                    $bound_k = rand(1, 20);
                    for($k = 0; $k < $bound_k; $k++) {
                        $reply = factory(App\Reply::class)->make();
                        $reply->save();
                        // a answer has replies
                        $answer->replies()->save($reply);

                        // a user post a replies
                        $user = App\User::all()->random(1);
                        $user->replies()->save($reply);

                        // an reply can be voted up by many users
                        $bound_f_1 = rand(1, 10);
                        $bound_f_2 = rand(11, 20);
                        for($f = $bound_f_1; $f < $bound_f_2; $f++) {
                            $reply->vote_up_users()->attach($f);
                        }

                    }

                    $question->answers()->save($answer);
                }

                // question have many replies
                $bound_j = rand(5, 20);
                for($j = 0; $j < $bound_j; $j++) {
                    $reply = factory(App\Reply::class)->make();
                    $reply->save();

                    // a reply is post by a user
                    App\User::all()->random(1)->replies()->save($reply);

                    $question->replies()->save($reply);

                    // a reply can be voted up by many users
                    $bound_f = rand(1, 5);
                    for($f = 0; $f < $bound_f; $f++) {
                        $reply->vote_up_users()->attach(rand(1, 50));
                    }
                }

            }
        }


        // create replies to replies
        $numReplies = App\Reply::count();
        for($i = 1; $i < 1000; $i++) {
            $reply_1 = App\Reply::find(rand(1, $numReplies));
            $reply_2 = App\Reply::find(rand(1, $numReplies));
            $reply_1->receive_replies()->save($reply_2);
        }
    }
}
