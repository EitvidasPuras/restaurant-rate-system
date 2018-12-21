<?php

namespace App\Http\Controllers;

use App\Restaurant;
use Illuminate\Http\Request;
use App\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Cookie;
use Lcobucci\JWT\Parser;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurants = Type::all();
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
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $type = new Type;
        $type->name = $request->name;
        $type->save();
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
        $type = Type::find($id);
        if (!empty($type)) {
            return response($type, 200);
        }
        return response()->json("Type not found", 404);
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

        $type = Type::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|max:16',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $type->update($request->all());
        return response()->json("Success", 200);
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

        $type = Type::findOrFail($id);

        $restaurants = Restaurant::where('type_id', '=', $id)->get();

        foreach ($restaurants as $restaurant) {
            DB::table('comments')
                ->where('restaurant_id', '=', $restaurant->id)->delete();
            DB::table('ratings')
                ->where('restaurant_id', '=', $restaurant->id)->delete();
            $restaurant->delete();
        }
        $type->delete();
        return response()->json("Success", 200);
    }
}
