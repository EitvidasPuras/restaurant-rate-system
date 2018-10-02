<?php

use Illuminate\Database\Seeder;
use App\Type;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type = new Type();
        $type->name = "Nightclub";
        $type->save();

        $type = new Type();
        $type->name = "Lounge";
        $type->save();

        $type = new Type();
        $type->name = "Restaurant";
        $type->save();
    }
}
