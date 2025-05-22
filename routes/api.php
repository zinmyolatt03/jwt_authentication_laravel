<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], function(){

    Route::post("register", [AuthController::class, 'register']);   
    Route::post("login", [AuthController::class, 'login']);   
    Route::get("profile", [ AuthController::class, 'profile'])->middleware("jwtauthmiddleware");
    Route::post('logout', [ AuthController::class, 'logout'])->middleware('jwtauthmiddleware');
    Route::post('refresh', [ AuthController::class, 'refresh']);

} );