<?php

use App\User;
use App\Reply;
use App\Answer;
use Illuminate\Database\Seeder;

class QuestionAnswerVoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Answer::all() as $answer) {
            foreach (User::all()->random(rand(3, 50)) as $user) {
                $answer->vote_up_users()->save($user);
            }

            foreach (User::all()->random(rand(3, 20)) as $user) {
                $answer->vote_down_users()->save($user);
            }
        }

        foreach (Reply::all() as $reply) {
            foreach (User::all()->random(rand(3, 50)) as $user) {
                $reply->vote_up_users()->save($user);
            }
        }


    }
}
