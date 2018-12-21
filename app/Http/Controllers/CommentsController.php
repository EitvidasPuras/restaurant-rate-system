<?php

namespace App\Http\Controllers;

use App\Restaurant;
use Illuminate\Http\Request;
use App\Comment;
use Illuminate\Support\Facades\Validator;
use Cookie;
use Lcobucci\JWT\Parser;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Token;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::all();
        foreach ($comments as $comment) {
            $comment->user;
            $comment->restaurant;
            $comment->restaurant->type;
        }
        return response($comments, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $restaurant = Restaurant::find($request->restaurant_id);
        $token = Cookie::get('JWT-TOKEN');
        $token = (new Parser())->parse((string)$token);

        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'bail|required',
            'text' => 'bail|required|max:501',
            'rating' => 'bail|required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $alreadyCommented = DB::table('comments')
            ->where('user_id', '=', $token->getClaim('uid'))
            ->where('restaurant_id', '=', $request->restaurant_id)
            ->get();

        $alreadyRated = DB::table('ratings')
            ->where('user_id', '=', $token->getClaim('uid'))
            ->where('restaurant_id', '=', $request->restaurant_id)
            ->get();

        if (!$alreadyCommented->isEmpty() && !$alreadyRated->isEmpty()) {
            return response()->json("You already reviewed this restaurant", 400);
        }

        $comment = new Comment;
        $comment->user_id = $token->getClaim('uid');
        $comment->restaurant_id = $request->restaurant_id;
        $comment->text = $request->text;
        $comment->save();

        DB::table('ratings')->insert(
            ['user_id' => $token->getClaim('uid'),
                'restaurant_id' => $request->restaurant_id,
                'rating' => $request->rating]
        );

        $newAverage = ($restaurant->total_count * $restaurant->average_rating + $request->rating) / ($restaurant->total_count + 1);
        DB::table('restaurants')->where('id', $request->restaurant_id)
            ->update(['total_count' => $restaurant->total_count + 1, 'average_rating' => $newAverage]);

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
        $comment = Comment::find($id);
        if (!empty($comment)) {
            $comment->user;
            $comment->restaurant;
            $comment->restaurant->type;
            return response($comment, 200);
        }
        return response()->json("Comment not found", 404);
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

        $comment = Comment::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'bail|required|digits:1|integer',
            'text' => 'bail|required|max:255',
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }
        $comment->update($request->all());
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
            return response("Not an admin", 400);
        }

        $comment = Comment::find($id);
        if (!empty($comment)) {
            $comment->delete();
            return response("", 200);
        }
        return response("", 404);
    }
}
