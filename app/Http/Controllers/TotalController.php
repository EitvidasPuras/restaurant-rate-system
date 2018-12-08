<?php

namespace App\Http\Controllers;

use App\Restaurant;
use Illuminate\Http\Request;
use Cookie;
use Lcobucci\JWT\Parser;
use App\User;

class TotalController extends Controller
{
    public function hubIndex()
    {
        $token = Cookie::get('JWT-TOKEN');
        $token = (new Parser())->parse((string)$token);
        $user = User::find($token->getClaim('uid'));
        return view('hub')->with('user', $user);
    }

    public function homeIndex()
    {
        $restaurants = Restaurant::all();
        return view('homepage')->with('restaurants', $restaurants);
    }


}
