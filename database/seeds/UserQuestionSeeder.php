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
        factory(App\User::class, 50)->create()->each(function($u) {
            // a user can post many question
            $bound_i = rand(5, 10);
            for($i = 0; $i < $bound_i; $i++) {
                $question = factory(App\Question::class)->make();
                $question->save();

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
                        $answer->replies()->save($reply);
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
                }

                // an question is posted by user
                $u->questions()->save($question);
            }
        });
    }
}
