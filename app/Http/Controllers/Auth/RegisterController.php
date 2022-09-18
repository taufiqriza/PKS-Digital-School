<?php

namespace App\Http\Controllers\Auth;

use App\Users;
use App\OtpCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        
        $allRequest = $request->all() ;
        
        $validator = Validator::make($allRequest, [
            'name'   => 'required',
            'email' => 'required|unique:users,email|email',
            'user_name' => 'required|unique:users,user_name'
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user =Users::create( [
            "user_name" => $request->user_name,
            "email" => $request->email,
            "name" => $request->name,
            "password" => Hash::make($request->password)
        ]);
        
        do {
            $random = mt_rand(100000 , 999999);
            $check = OtpCode::where('otp' , $random)->first();

        } while ($check);

        $now = carbon::now();
        
        $otp_code = OtpCode::create([
            'otp' => $random,
            'valid_until' =>        $now->addMinutes(5),
            'user_id' => $user->id
        ]);

            
        return response()->json([
            'success' => true,
            'massage' => 'Data User berhasil ditambahkan' ,
            'data' => [
                'user' => $user,
                'otp_code' => $otp_code
            ]
            ]);
    }
}
