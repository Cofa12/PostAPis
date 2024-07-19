<?php

namespace App\Http\Controllers\post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try{
            $posts = DB::table('posts')->get();
            return response()->json([
                'status' => true,
                'message'=>'returned all posts',
                'data' => $posts
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message' =>'Server Error',
                'error'=>$e->getMessage()
            ],500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validator = validator::make($request->all(),[
                'title'=>'required|string|max:20',
                'description' =>'required|string|max:255',
                'user_id' => 'required|int'
            ]);

            if($validator->fails()){
                response()->json([
                    'status'=>false,
                    'message'=>'validation Error',
                    'error'  => $validator->errors()
                ],422);
            }

            $insertedDataStatus = DB::table('posts')->insert([
                'title' => $request->title,
                'description'=>$request->description,
                'user_id' => $request->user_id
            ]);

            if($insertedDataStatus){
                return response()->json([
                    'status'=>true,
                    'message'=>'post add successfully'
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try{
            $post = DB::table('posts')->where('id',$id)->first();
            if(!$post){
                return response()->json([
                    'status' => false,
                    'message'=>'Not fount post',
                ],404);            }
            return response()->json([
                'status' => true,
                'message'=>'returned a post',
                'data' => $post
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message' =>'Server Error',
                'error'=>$e->getMessage()
            ],500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try{
            $validator = validator::make($request->all(),[
                'title'=>'required|string|max:20',
                'description' =>'required|string|max:255',
            ]);

            if($validator->fails()){
                response()->json([
                    'status'=>false,
                    'message'=>'validation Error',
                    'error'  => $validator->errors()
                ],422);
            }
            $post = DB::table('posts')->where('id',$id)->update([
                'title'=>$request->title,
                'description' => $request->description,
            ]);

            if($post){
                return response()->json([
                    'status' => true,
                    'message'=>'post has been updated',
                ],200);
            }else {
                return response()->json([
                    'status' => false,
                    'message'=>'Not fount post',
                ],404);
            }
        }catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message' =>'Server Error',
                'error'=>$e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try{
            $post = DB::table('posts')->where('id',$id)->delete();
            return response()->json([
                'status' => true,
                'message'=>'deleting a post successfully',
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'status'=>false,
                'message' =>'Server Error',
                'error'=>$e->getMessage()
            ],500);
        }
    }
}
