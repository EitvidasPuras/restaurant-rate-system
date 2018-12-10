<?php

namespace App\Http\Controllers;

use App\Restaurant;
use Illuminate\Http\Request;
use Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        if (!Auth::check() and (Cookie::get('JWT-TOKEN'))) {
            return redirect('/loginwithtoken');
        }

        $restaurants = DB::table('restaurants')->simplePaginate(6);
        return view('homepage')->with('restaurants', $restaurants);
    }

    public function showRestaurant($id)
    {
        $restaurant = Restaurant::find($id);
        return view('restaurant')
            ->with('restaurant', $restaurant);
    }
}
