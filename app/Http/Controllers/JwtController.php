<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Cookie;
use Lcobucci\JWT\Parser;
use App\User;

class JwtController extends Controller
{
    public function getAuthenticatedUser()
    {
        if (!$token = Cookie::get('JWT-TOKEN')) {
            return response()->json('Cookie not found', 404);
        }
        $token = (new Parser())->parse((string)$token);
        $uid = $token->getClaim('uid');

        if (!$user = User::find($uid)) {
            return response()->json('User not found', 404);
        }

        return response()->json(compact('user'));
    }

    public function loginUsingToken()
    {
        $token = Cookie::get('JWT-TOKEN');
        $token = (new Parser())->parse((string)$token);
        $uid = $token->getClaim('uid');
        Auth::loginUsingId($uid);
        return redirect('/');
    }
}
