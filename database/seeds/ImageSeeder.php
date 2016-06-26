<?php

use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // generate dummy image in database , please make sure there are exist file
        for($i = 1; $i <= 100; $i++) {
            $newImg = App\Image::create([
                'reference_id' => $i,
                'path' => 'images/img-' . $i . '.png',
                'width' => 50,
                'height' => 50,
            ]);
            $newImg->save();
        }

        // associate each user and topic with img
        $i = 1;
        foreach (App\User::all() as $user) {
            $settings = $user->settings;
            $settings->profile_pic_id = $i;
            $settings->save();
            $i++;
        }

        foreach (App\Topic::all() as $topic) {
            $topic->avatar_img_id = $i;
            $topic->save();
            $i++;
        }
    }
}
