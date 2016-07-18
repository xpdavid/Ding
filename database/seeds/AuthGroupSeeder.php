<?php

use Illuminate\Database\Seeder;
use App\AuthGroup;
use App\Authority;

class AuthGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create authorities first

        // authority
        Authority::create([
            'type' => 1,
            'description' => 'View Question'
        ]);

        Authority::create([
            'type' => 2,
            'description' => 'Ticket'
        ]);

        Authority::create([
            'type' => 3,
            'description' => 'View Answer'
        ]);

        Authority::create([
            'type' => 4,
            'description' => 'Post Question'
        ]);

        Authority::create([
            'type' => 5,
            'description' => 'Post Answer'
        ]);

        Authority::create([
            'type' => 6,
            'description' => 'Vote Answer'
        ]);

        Authority::create([
            'type' => 7,
            'description' => 'Vote Reply'
        ]);

        Authority::create([
            'type' => 8,
            'description' => 'Send Message'
        ]);

        Authority::create([
            'type' => 9,
            'description' => 'Edit Question'
        ]);

        Authority::create([
            'type' => 10,
            'description' => 'Personalize URL'
        ]);

        Authority::create([
            'type' => 11,
            'description' => 'Add Topic'
        ]);

        Authority::create([
            'type' => 12,
            'description' => 'Edit Topic'
        ]);

        Authority::create([
            'type' => 13,
            'description' => 'Merge Topic'
        ]);

        Authority::create([
            'type' => 14,
            'description' => 'Close Invalid Topic'
        ]);

        Authority::create([
            'type' => 15,
            'description' => 'Close Invalid Question'
        ]);

        Authority::create([
            'type' => 16,
            'description' => 'Close Invalid Answer'
        ]);

        Authority::create([
            'type' => 17,
            'description' => 'Ban User'
        ]);

        // associate with group
        AuthGroup::create([
            'name' => 'v1',
            'point' => 0
        ])->authorities()->attach([1,2,3,4,5,6,7,8]);

        AuthGroup::create([
            'name' => 'v2',
            'point' => 80
        ])->authorities()->attach([1,2,3,4,5,6,7,8,9]);

        AuthGroup::create([
            'name' => 'v3',
            'point' => 200
        ])->authorities()->attach([1,2,3,4,5,6,7,8,9,10]);

        AuthGroup::create([
            'name' => 'v4',
            'point' => 320
        ])->authorities()
            ->attach([1,2,3,4,5,6,7,8,9,10,11,12,13]);

        AuthGroup::create([
            'name' => 'v5',
            'point' => 500
        ])->authorities()
            ->attach([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16]);

        AuthGroup::create([
            'name' => 'moderator',
            'point' => 0
        ])->authorities()
            ->attach([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16]);

        AuthGroup::create([
            'name' => 'admin',
            'point' => 0
        ])->authorities()
            ->attach([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17]);


        // baned user
        AuthGroup::create([
            'name' => 'v0',
            'point' => 0
        ])->authorities()
            ->attach(1);;

        // Unregistered User
        AuthGroup::create([
            'name' => 'Unregistered User',
            'point' => 0
        ])->authorities()
            ->attach(1);



    }
}
