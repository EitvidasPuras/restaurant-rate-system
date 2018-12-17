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
            return response()->json("Not an admin", 400);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|max:16',
            'description' => 'bail|required|max:601',
            'image' => 'bail|mimes:jpg,jpeg,png|required|max:2048',
            'seats' => 'bail|required',
            'type_id' => 'bail|required',
        ]);

        if ($validator->fails()) {
            return response()->json("Validation failed", 400);
        }

        if ($request->hasFile('image')) {
            $fileNameWithExtension = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $request->file('image')->storeAs('public/restaurant_images/', $fileNameToStore);
        } else {
            $fileNameToStore = 'placeholder.jpeg';
        }

        $restaurant = new Restaurant;
        $restaurant->name = $request->name;
        $restaurant->description = $request->description;
        $restaurant->image = $fileNameToStore;
        $restaurant->seats = $request->seats;
        $restaurant->type_id = $request->type_id;
        $restaurant->save();
        return response()->json("Success", 201);
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
            return response()->json("Not an admin", 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|max:16',
            'description' => 'bail|required|max:601',
            'image' => 'bail|mimes:jpg,jpeg,png|max:2048',
            'seats' => 'bail|required',
            'type_id' => 'bail|required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $restaurant = Restaurant::findOrFail($id);

        if ($request->hasFile('image')) {
            $fileNameWithExtension = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $request->file('image')->storeAs('public/restaurant_images/', $fileNameToStore);

            $restaurant->name = $request->name;
            $restaurant->description = $request->description;
            $restaurant->image = $fileNameToStore;
            $restaurant->seats = $request->seats;
            $restaurant->type_id = $request->type_id;
            $restaurant->save();
        } else {
            $restaurant->name = $request->name;
            $restaurant->description = $request->description;
            $restaurant->seats = $request->seats;
            $restaurant->type_id = $request->type_id;
            $restaurant->save();
        }

//        $restaurant->update($request->all());
        return response()->json("Success", 201);
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
            return response()->json("Not an admin", 400);
        }

        $restaurant = Restaurant::find($id);
        if (!empty($restaurant)) {
            $restaurant->delete();
            DB::table('comments')->where('restaurant_id', '=', $id)->delete();
            DB::table('ratings')->where('restaurant_id', '=', $id)->delete();
            return response()->json("Success", 200);
        }
        return response()->json("Restaurant not found", 404);
    }
}
