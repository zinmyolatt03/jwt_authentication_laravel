<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\HttpResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Api\RegisterRequest;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{

    use HttpResponse;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function profile()
    {
        $user = $this->userRepository->getProfile();
        return $this->success('success', 200, 'user profile reterived successfully', [ 'user' => new UserResource($user)]);
    }

    public function logout()
    {
        $this->userRepository->logoutUser();
        return $this->success('success', 200, 'user logged out successfully', null )->withCookie("refresh_token");
    }

    public function refresh()
    {
        $token = $this->userRepository->refreshToken();
        if($token){
            return $this->success('success', 200, 'refresh token reterived successfully',  [ 'token' => $token]);
        }

        return $this->fail('fail', 401, 'erro', null);
    }

    
    public function register(RegisterRequest $request){

        $refresh_token = Str::random(64);
        $validated_data = $request->validated();
        $validated_data['refresh_token'] = $refresh_token;
        $user = $this->userRepository->createUser($validated_data);
        $token = JWTAuth::fromUser($user);
        return $this->success('success', 201, 'user registered successfully', [ 'user' => new UserResource($user), 'token' => $token ])
        ->cookie('refresh_token', $refresh_token, 1440, null, null, true, true, false, "Strict");

    }

    public function login(LoginRequest $request){
        $validated_data = $request->validated();
        $data = $this->userRepository->loginUser($validated_data);
        if(! $data){
            return $this->fail('fail', 400, 'validation error', 'your password is incorrect');
        }
        $token = JWTAuth::fromUser($data['user']);
        return $this->success('success', 200, 'user logged in successfully', [ 'user' => new UserResource($data['user']), 'token' => $token])
        ->cookie("refresh_token", $data['refresh_token'], 1440, null, null, true, true, false, "Strict" );
    }
}
