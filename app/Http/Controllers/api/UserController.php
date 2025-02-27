<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        if(!$request->user()) {
            return response()->json(['message'=>'Not Authenticated'],500);
        }  

        return response()->json([
            'message'=>'Profile Fetched',
            'data'=>$request->user()
        ],200);
    }
}
