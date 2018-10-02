<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Restaurant;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurants = Restaurant::all();
        return response($restaurants, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|max:255',
            'description' => 'bail|required|max:255',
            'seats' => 'bail|required|digits_between:1,3|integer',
            'type_id' => 'bail|required|digits:1|integer',
        ]);

        if ($validator->fails()) {
            return response("", 400);
        }
        $restaurant = new Restaurant;
        $restaurant->name = $request->name;
        $restaurant->description = $request->description;
        $restaurant->image = $request->image;
        $restaurant->seats = $request->seats;
        $restaurant->type_id = $request->type_id;
        $restaurant->save();
        return response("", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return $restaurant;
//        $restaurant = Restaurant::find($id);
//        if (!empty($restaurant)) {
//            return response($restaurant, 200);
//        }
//        return response("", 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|max:255',
            'description' => 'bail|required|max:255',
            'seats' => 'bail|required|digits_between:1,3|integer',
            'type_id' => 'bail|required|digits:1|integer',
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }
        $restaurant->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();
    }
}
