<?php

use Illuminate\Database\Seeder;

class SubscribeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (App\User::all() as $user) {
            // for subscribe question
            $bound_i = rand(0, 20);
            $visited = [];
            for($i = 1; $i < $bound_i; $i++) {
                $random = rand(1, 100);
                if(!in_array($random, $visited)) {
                    $user->subscribe->questions()->attach($random);
                }
            }

            // for subscribe topic
            $bound_i = rand(0, 20);
            $visited = [];
            for($i = 1; $i < $bound_i; $i++) {
                $random = rand(1, 50);
                if(!in_array($random, $visited)) {
                    $user->subscribe->topics()->attach($random);
                }
            }

            // for subscribe user
            $bound_i = rand(0, 20);
            $visited = [];
            for($i = 1; $i < $bound_i; $i++) {
                $random = rand(1, 50);
                if(!in_array($random, $visited)) {
                    $user->subscribe->users()->attach($random);
                }
            }
        }
    }
}
