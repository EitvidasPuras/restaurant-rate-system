<?php

use Illuminate\Database\Seeder;
use App\Rating;

class RatingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 15; $i++) {
            $rating = new Rating();
            $rating->user_id = rand(1, 3);
            $rating->restaurant_id = rand(1, 9);
            $rating->rating = rand(1, 5);
            $rating->save();
        }
    }
}
