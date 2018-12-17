<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Cookie;
use Lcobucci\JWT\Parser;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = Cookie::get('JWT-TOKEN');
        $token = (new Parser())->parse((string)$token);
        if (!$token->getClaim('admin')) {
            return response("Not an admin", 400);
        }
        return $next($request);
    }
}
