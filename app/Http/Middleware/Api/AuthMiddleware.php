<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\HttpResponse;


class AuthMiddleware
{
    use HttpResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            JWTAuth::parseToken()->authenticate();        
        }catch(JWTException $error){
            return $this->fail('fail', 401, 'user is not allowed to go to this page', $error->getMessage());
        }

        return $next($request);
    }
}
