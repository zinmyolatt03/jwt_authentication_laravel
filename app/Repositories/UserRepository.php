<?php

namespace app\Repositories;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository{

    use HttpResponse;

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
            return $user;
        }
        return null;
    }
}