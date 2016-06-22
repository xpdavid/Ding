<?php

use Illuminate\Database\Seeder;

class TopicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Topic::class, 50)->create();

        for($i = 1; $i <= 10; $i++) {
            $topic = App\Topic::find($i);
            for($j = 1; $j <= 40; $j++) {
                $sub_topic = App\Topic::find(rand(11, 50));
                $topic->child_topics()->save($sub_topic);
            }
        }
        
    }
}
