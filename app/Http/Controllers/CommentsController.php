<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Illuminate\Support\Facades\Validator;
use Cookie;
use Lcobucci\JWT\Parser;
use Illuminate\Support\Facades\DB;

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
        $token = Cookie::get('JWT-TOKEN');
        $token = (new Parser())->parse((string) $token);

        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'bail|required|digits:1|integer',
            'text' => 'bail|required|max:255',
        ]);

        if ($validator->fails()) {
            return response("", 400);
        }

        $alreadyRated = DB::table('comments')
            ->where('user_id', '=', $token->getClaim('uid'))
            ->where('restaurant_id', '=', $request->restaurant_id)
            ->get();
        if(!$alreadyRated->isEmpty()){
            return response("User already commented on this restaurant", 400);
        }

        $comment = new Comment;
        $comment->user_id = $token->getClaim('uid');
        $comment->restaurant_id = $request->restaurant_id;
        $comment->text = $request->text;
        $comment->save();
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
        $comment = Comment::find($id);
        if (!empty($comment)) {
            $comment->user;
            $comment->restaurant;
            $comment->restaurant->type;
            return response($comment, 200);
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
        $token = (new Parser())->parse((string) $token);
        if(!$token->getClaim('admin')){
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
        $token = (new Parser())->parse((string) $token);
        if(!$token->getClaim('admin')){
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
