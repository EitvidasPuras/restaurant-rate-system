<?php

namespace App\Http\Controllers;

use App\Restaurant;
use App\Type;
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

    public function adminPanelIndex()
    {
        return view('admin.panel');
    }

    public function adminRestaurantsIndex()
    {
        $restaurants = Restaurant::orderBy('created_at', 'desc')->simplePaginate(6);
        $types = Type::all();
        return view('admin.restaurants')->with('restaurants', $restaurants)
            ->with('types', $types);
    }

    public function adminUsersIndex()
    {
        $users = User::orderBy('created_at', 'desc')->simplePaginate(6);
        return view('admin.users')->with('users', $users);
    }
}
