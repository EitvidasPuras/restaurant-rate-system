<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use JWTAuth;
use Cookie;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        $data = new ValidationData();
//        $data->setIssuer('http://127.0.0.1:8000/login');
//        $data->setCurrentTime(time());
//            if(!$token = Cookie::get('JWT-TOKEN')){
//                return response()->json(['status' => 'Cookie not found'], 404);
//            }
//            $token = (new Parser())->parse((string) $token);
//            if(!$token->validate($data) or !$token->verify(new Sha256(), env('JWT_SECRET'))){
//                return response()->json(['status' => 'Token invalid'], 400);
//            }
//        return response($token);

        $data = new ValidationData();
        $data->setIssuer('http://127.0.0.1:8000/login');
        $data->setCurrentTime(time());
        $data->setId(env('JWT_ID'));
        if(!$token = Cookie::get('JWT-TOKEN')){
            return response()->json('Cookie not found', 404);
        }
        $token = (new Parser())->parse((string) $token);
        if(!$token->validate($data) or !$token->verify(new Sha256(), env('JWT_SECRET'))){
            return response()->json('Token invalid', 400);
        }
        return $next($request);
    }
}
