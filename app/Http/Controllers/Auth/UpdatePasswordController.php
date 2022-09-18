<?php

namespace App\Http\Controllers\Auth;

use App\Users;
use App\OtpCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
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


            //set validation
            $validator = Validator::make($request->all(), [
                'email'   => 'required',
                'password' => 'required|confirmed|min:6',
                
            ]);
            
            //response error validation
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = Users::where('email' , $request->email)->first();

            if(!$user) {
                return response()->json([
                    'success' => false,
                    'massage' => 'email tidak ditemukan'
                ], 400);
            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'massage' => 'pasword berhasil di ubah',
                'data' => $user
            ]);
    }
}
