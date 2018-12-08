<?php

namespace App\Http\Controllers;

use App\Rating;
use Illuminate\Http\Request;
use App\Restaurant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Lcobucci\JWT\Parser;
use Illuminate\Support\Facades\DB;

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
        foreach ($restaurants as $restaurant) {
            $restaurant->type;
            $restaurant->comments;
            foreach ($restaurant->comments as $comment) {
                $comment->user;
            }
            $restaurant->ratings;
            foreach ($restaurant->ratings as $rating) {
                $rating->user;
            }
        }
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
        $token = Cookie::get('JWT-TOKEN');
        $token = (new Parser())->parse((string)$token);
        if (!$token->getClaim('admin')) {
            return response("Not an admin", 400);
        }
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
        $restaurant = Restaurant::find($id);
        if (!empty($restaurant)) {
            $restaurant->type;
            return response($restaurant, 200);
        }
        return response("", 404);
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
        $token = Cookie::get('JWT-TOKEN');
        $token = (new Parser())->parse((string)$token);
        if (!$token->getClaim('admin')) {
            return response("Not an admin", 400);
        }

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
        $token = Cookie::get('JWT-TOKEN');
        $token = (new Parser())->parse((string)$token);
        if (!$token->getClaim('admin')) {
            return response("Not an admin", 400);
        }

        $restaurant = Restaurant::find($id);
        if (!empty($restaurant)) {
            $restaurant->delete();
            DB::table('comments')->where('restaurant_id', '=', $id)->delete();
            DB::table('ratings')->where('restaurant_id', '=', $id)->delete();
            return response("", 200);
        }
        return response("", 404);
    }

    public function rateRestaurant(Request $request)
    {
        $restaurant = Restaurant::find($request->restaurant_id);
        $token = Cookie::get('JWT-TOKEN');
        $token = (new Parser())->parse((string)$token);
        $alreadyRated = DB::table('ratings')
            ->where('user_id', '=', $token->getClaim('uid'))
            ->where('restaurant_id', '=', $request->restaurant_id)
            ->get();
        if (!$alreadyRated->isEmpty()) {
            return response("User already rated this restaurant", 400);
        }
        DB::table('ratings')->insert(
            ['user_id' => $token->getClaim('uid'), 'restaurant_id' => $request->restaurant_id, 'rating' => $request->rating]
        );

        $newAverage = ($restaurant->total_count * $restaurant->average_rating + $request->rating) / ($restaurant->total_count + 1);
        DB::table('restaurants')->where('id', $request->restaurant_id)
            ->update(['total_count' => $restaurant->total_count + 1, 'average_rating' => $newAverage]);

    }
}
