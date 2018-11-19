<?php

use Illuminate\Database\Seeder;
use App\Restaurant;

class RestaurantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $restaurant = new Restaurant();
            $restaurant->name = "Restaurant" . $i;
            $restaurant->description = "Test description";
            $restaurant->seats = rand(5, 20);
            $restaurant->type_id = rand(1, 3);
//            $restaurant->total_count = rand(1, 15);
//            $restaurant->average_rating = rand(10, 50) / 10;
            $restaurant->save();
        }
    }
}
