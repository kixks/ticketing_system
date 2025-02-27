<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) : JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255', 
            'password' => 'required|string|min:8|max:255',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $user = User::where('email',$request->email)->first();

        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json(['message'=>'Invalid Credentials Inputted'],401);
        }

        $token = $user->createToken($user->name.'Auth-Token')->plainTextToken;

        return response()->json(['message'=>'Login Successful',
                                 'token_type'=>'Bearer',
                                 'token'=>$token],200);

    }
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255', 
            'email' => 'required|email|unique:users,email|max:255', 
            'password' => 'required|string|min:8|max:255'
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if(!$user){
            return response()->json(['message'=>'Something went wrong while registering'],500);
        }

        $token = $user->createToken($user->name.'Auth-Token')->plainTextToken;

        return response()->json(['message'=>'Registration Successful',
                                 'token_type'=>'Bearer',
                                 'token'=>$token],201);
        
    }

    public function profile(Request $request): JsonResponse
    {
        if(!$request->user()) {
            return response()->json(['message'=>'Not Authenticated'],500);
        }  

        return response()->json([
            'message'=>'Profile Fetched',
            'data'=>$request->user()
        ],200);
    }

      public function logout(Request $request): JsonResponse
    {
        $user = User::where('id',$request->user()->id)->first();
        
        if(!$user){
            return response()->json(['message'=>'User not found'],404);
        }
        $user->tokens()->delete();

        return response()->json(['message'=>'Logged out successfully'],200);
    }
}
