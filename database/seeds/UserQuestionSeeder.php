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
            for($i = 0; $i < rand(5, 10); $i++) {
                $question = factory(App\Question::class)->make();
                $question->save();

                // question is categorized by many topics
                for($j = 0; $j < rand(1, 8); $j++) {
                    $question->topics()->save(App\Topic::all()->random(1));
                }

                // question have many answers
                for($j = 0; $j < rand(1, 20); $j++) {
                    $answer = factory(App\Answer::class)->make();

                    // an answer belongs to a user
                    App\User::all()->random(1)->answers()->save($answer);

                    // an answer have many replies
                    for($j = 0; $j < rand(5, 20); $j++) {
                        $reply = factory(App\Reply::class)->make();
                        $reply->save();
                        $answer->replies()->save($reply);
                    }

                    $question->answers()->save($answer);
                }

                // question have many replies
                for($j = 0; $j < rand(3, 20); $j++) {
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
