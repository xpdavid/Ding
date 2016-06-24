<?php

use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // random create notification for user
        foreach (App\User::all() as $user) {
            $bound_i = rand(5, 20);
            for($i = 0; $i < $bound_i; $i++) {
                $user->notifications()->save(App\Notification::createFromType(rand(1, 11), rand(1, 50), rand(1, 50)));
            }
        }
    }
}
