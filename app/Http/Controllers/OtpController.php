<?php

namespace App\Http\Controllers;
use App\Models\Otp;
use Illuminate\Http\Request;

class OtpController extends Controller
{

    public function __construct(){
        $this->otp =  new Otp();
    }

    public function sendOtp (Request $request){
        $this->validate($request, [
            'phone_number' => 'required',
        ]);

        $parameters =  $request->all();

        $phone = $parameters['phone_number'];
        $code = $this->otp->generateOtpCode();
        $result = $this->otp->sendSmartSms($phone, $code);
        $isSuccess = $result->success ?? false;

        if($isSuccess){
            $result->code = $code;
            return response()->json(["data"=>$result, 'message'=>'otp sent'], 200);
        }
        return response()->json(["data"=>$result, 'message'=>'Could not send otp'], 400);
    }


    public function mockSendOtp (Request $request){
        $this->validate($request, [
            'phone_number' => 'required',
        ]);

        $isSuccess = true;

        $res = ["success" => true,
        "comment" => "SMS OTP sent successfully",
        "log_id" => "sotp-20240529-q6KZUEtCw0uZvx7hEgvdNf1LSK",
        "ref_id" => "66578478f09d0",
        "code" => 2327];

        if($isSuccess){
             return response()->json(["data"=>$res, 'message'=>'otp sent'], 200);
        }
        return response()->json(["data"=>$res, 'message'=>'otp sent'], 400);
    }


}
