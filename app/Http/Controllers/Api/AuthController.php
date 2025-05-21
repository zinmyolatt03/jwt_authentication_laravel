<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    use HttpResponse;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    
    public function register(RegisterRequest $request){

        $refresh_token = Str::random(64);
        $validated_data = $request->validated();
        $validated_data['refresh_token'] = $refresh_token;
        $user = $this->userRepository->createUser($validated_data);
        $token = JWTAuth::fromUser($user);
        return $this->success('success', 201, 'user registered successfully', [ 'user' => new UserResource($user), 'token' => $token ]);

    }

    public function login(LoginRequest $request){
        $validated_data = $request->validated();
        $user = $this->userRepository->loginUser($validated_data);
        if(! $user){
            return $this->fail('fail', 400, 'validation error', 'your password is incorrect');
        }
        $token = JWTAuth::fromUser($user);
        return $this->success('success', 200, 'user logged in successfully', [ 'user' => new UserResource($user), 'token' => $token]);
    }
}
