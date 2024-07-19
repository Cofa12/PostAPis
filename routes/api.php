<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\post\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('login',[AuthController::class,'login']);
Route::post('register',[AuthController::class,'register']);
Route::group(['middleware'=>'auth:sanctum'],function (){
    Route::post('logout',[AuthController::class,'logout']);

});
Route::middleware('verify.token')->group(function (){
    Route::apiResource('posts',PostController::class);
});

/*
Route::get('pass',function (){
    return Hash::make('Cofa20##20##');
});
Route::get('pass',function (){
    return Hash::make('Cofa20##20##');
});

*/
