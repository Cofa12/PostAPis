<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Cassandra\Exception\InvalidQueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;




class AuthController extends Controller
{
    //
    use HasApiTokens;
    public function login(Request $request){
        try{
            $validator = validator::make($request->all(),[
                'email'=>'required|string|email|',
                'password'=>'required|string|max:30'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'validation error',
                    'errors'=>$validator->errors()
                ],401);
            }
           $user = Auth::attempt($request->only(['email','password']));
            if(!$user){
                return response()->json([
                    'status'=>false,
                    'message'=>'Email or password not correct'
                ],401);
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('api-Token')->plainTextToken;
            return response()->json([
                'status'=>true,
                'message'=>'user logged in successfully',
                'data'=> [
                    'user'=>$user,
                    'token'=>$token
                ]
            ],200);
        }
        catch(\Exception $e){
            return response()->json([
                'status'=>false,
                'message' =>'Server Error',
                'error'=>$e->getMessage()
            ],500);
        }
    }

    public function register(Request $request){
        try {
            $validator = validator::make($request->all(),[
                'name'=>'required|string|max:30',
                'email'=>'required|string|email|unique:users',
                'password'=>'required|string|max:30'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'validation error',
                    'errors'=>$validator->errors()
                ],401);
            }
            $user = DB::table('users')->insert([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);
            if($user){
                return response()->json([
                    'status'=>true,
                    'message'=>'User has been created successfully',
                ],201);
            }

        }catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message' =>'Server Error',
                'error'=>$e->getMessage()
            ],500);
        }
    }

    public function logout(Request $request){
        try {
            if($request->user()->currentAccessToken()->delete()){
                return response()->json([
                    'status'=>true,
                    'message'=>'user has logged out successfully'
                ],200);
            }
        } catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message' =>'Server Error',
                'error'=>$e->getMessage()
            ],500);
        }
    }
}
