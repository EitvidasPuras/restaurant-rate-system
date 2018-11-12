<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->name = "User" . $i;
            $user->email = "user" . $i . "@user.com";
            $user->password = '$2y$10$irf.LShrorr6o.XzCTowuO/eJqkC8BXgVqRxXxJvF8.nuFTF2fzey';
            $user->save();
        }
    }
}
