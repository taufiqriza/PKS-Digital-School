<?php

namespace App\Http\Controllers\Auth;

use App\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;



class LoginController extends Controller
{
    public function __invoke(Request $request)
    {

            //set validation
            $request->validate([
                'email'   => 'required',
                'password' => 'required'
                
            ]);
            
            
            $credentials = request(['email', 'password']);

         
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['eror' => 'Unauthorized'], 401);
        }
        
        return response()->json([
            "message" => "success",
                'token' => $token
        ]);
    }
}
