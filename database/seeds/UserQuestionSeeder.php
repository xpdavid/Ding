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
        factory(App\User::class, 10)->create()->each(function($u) {
            // a user can post many question
            for($i = 0; $i < 4; $i++) {
                $question = factory(App\Question::class)->make();
                $question->save();

                // question is categorized by many topics
                for($j = 0; $j < rand(1, 4); $j++) {
                    $question->topics()->save(App\Topic::all()->random(1));
                }

                // question have many answers
                for($j = 0; $j < rand(1, 10); $j++) {
                    $answer = factory(App\Answer::class)->make();

                    // an answer have many replies
                    for($j = 0; $j < rand(5, 12); $j++) {
                        $reply = factory(App\Reply::class)->make();
                        $reply->save();
                        $answer->replies()->save($reply);
                    }

                    $question->answers()->save($answer);
                }

                // question have many replies
                for($j = 0; $j < rand(3, 6); $j++) {
                    $reply = factory(App\Reply::class)->make();
                    $reply->save();
                    $question->replies()->save($reply);
                }

                // an question is posted by user
                $u->questions()->save($question);
            }
        });
    }
}
