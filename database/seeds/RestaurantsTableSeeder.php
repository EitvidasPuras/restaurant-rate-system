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
            $restaurant->description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. In condimentum ex ut erat pharetra, id condimentum nisi condimentum. Suspendisse rhoncus efficitur mauris nec dapibus. Morbi euismod, urna blandit tincidunt mollis, dolor leo hendrerit risus, sit amet cursus mauris magna tempor ligula. Vestibulum pellentesque pretium eleifend. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Integer nunc libero, aliquam quis nunc sed, rutrum laoreet risus. Vivamus ut neque quis velit tincidunt dapibus. Fusce in posuere magna.";
            $restaurant->image = "placeholder.jpeg";
            $restaurant->seats = rand(5, 20);
            $restaurant->type_id = rand(1, 3);
//            $restaurant->total_count = rand(1, 15);
//            $restaurant->average_rating = rand(10, 50) / 10;
            $restaurant->save();
        }
    }
}
