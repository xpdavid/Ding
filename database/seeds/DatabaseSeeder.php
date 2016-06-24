<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UsersTableSeeder::class);

        $this->call(TopicTableSeeder::class);
        $this->call(UserQuestionSeeder::class);
        $this->call(QuestionAnswerVoteSeeder::class);
        $this->call(SubscribeSeeder::class);
        $this->call(NotificationSeeder::class);

        Model::reguard();
    }
}
