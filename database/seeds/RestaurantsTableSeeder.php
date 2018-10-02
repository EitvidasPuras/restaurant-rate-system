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
            $restaurant->save();
        }
    }
}
