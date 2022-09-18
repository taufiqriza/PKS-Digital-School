<?php

namespace App\Http\Controllers\Auth;

use App\Users;
use App\OtpCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
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
                'otp'   => 'required',
                
            ]);
            
            //response error validation
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $otp_code = OtpCode::where('otp' , $request->otp)->first();

            if(!$otp_code)
            {
                return response()->json([
                    'success' => false,
                    'massage' => 'otp code tidak ditemukan'
                ], 400);
            }

            $now = Carbon::now();
            if( $now > $otp_code->valid_until)
            {
                return response()->json([
                    'success' => false,
                    'massage' => 'otp code tidak berlaku lagi'
                ], 400);
            }

            $user = Users::find($otp_code->user_id);
            $user->update([
                'email_verified_at' => $now
            ]);

            $otp_code->delete();

            return response()->json([
                'success' => true,
                'massage' => 'User Berhasil diverifikasi',
                'data' => $user
            ]);
    }
}
