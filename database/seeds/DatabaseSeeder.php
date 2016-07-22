<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

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

        $this->call(AuthGroupSeeder::class);

        $user = User::create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL'),
            'password' => bcrypt(env('ADMIN_PWD')),
        ]);
        $user->authGroup_id = 7;
        $user->save();

        $this->call(NUSModuleSeeder::class);


        Model::reguard();
    }
}
