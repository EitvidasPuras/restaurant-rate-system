<?php

use Illuminate\Database\Seeder;
use App\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 5; $i++) {
            $restaurant = new Comment();
            $restaurant->user_id = rand(1, 3);
            $restaurant->restaurant_id = rand(1, 9);
            $restaurant->text = "Random comment" . $i;
            $restaurant->save();
        }
    }
}
