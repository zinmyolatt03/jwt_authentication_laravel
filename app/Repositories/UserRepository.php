<?php

namespace app\Repositories;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRepository{

    use HttpResponse;

    public function refreshToken()
    {
        $refresh_token = Cookie::get('refresh_token');
        $hashed_refresh_token = hash('sha256', $refresh_token);
        $user = User::where("refresh_token", $hashed_refresh_token)->first();
        if($user){
            $token = JWTAuth::fromUser($user);
            return $token;
        }
        return null;
    }

    public function getProfile()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    public function logoutUser()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function createUser($userData)
    {
        return User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']),
            'refresh_token' => hash("sha256", $userData['refresh_token'])
        ]);
    }

    public function loginUser($userData)
    {
        $user = User::where('email', $userData['email'])->first();
        if(Hash::check( $userData['password'], $user['password'])){
            $refresh_token = Str::random(64); 
            $user->update(['refresh_token' => hash("sha256", $refresh_token)]);
            return [
                'user' => $user,
                'refresh_token' => $refresh_token,
            ];
        }
        return null;
    }
}