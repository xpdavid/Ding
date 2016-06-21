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
            $numUser = rand(3, 50);
            foreach (User::all()->random($numUser) as $user) {
                if (rand(0, 1) == 1) {
                    $answer->vote_up_users()->save($user);
                } else {
                    $answer->vote_down_users()->save($user);
                }

            }
        }
        
    }
}
