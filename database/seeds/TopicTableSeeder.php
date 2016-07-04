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
        factory(App\Topic::class, 50)->create()->each(function($t) {
            // bookmark hit
            App\Hit::create([
                'owner_type' => 'App\Topic',
                'owner_id' => $t->id
            ]);
        });

        for($i = 1; $i <= 10; $i++) {
            $topic = App\Topic::find($i);
            for($j = 1; $j <= 10; $j++) {
                $sub_topic = App\Topic::find(rand(11, 50));
                $topic->subtopics()->save($sub_topic);
                $another_topic = rand(1, 10);
                if ($another_topic != $topic->id) {
                    App\Topic::find($another_topic)->subtopics()->save($sub_topic);
                }
            }
        }
        
    }
}
