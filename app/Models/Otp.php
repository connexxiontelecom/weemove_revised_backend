<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class Otp extends Model
{

    // Ensure that the model uses timestamps
    public $timestamps = true;

    // Define the fillable attributes
    protected $fillable = [
        'user_id', 'otp'
    ];

    public function sendOTPCode($phone){
        $code = $this->generateOtpCode();
        $result = $this->sendSms($phone);
        $resultCode = $result['ErrorCode'] ?? null;
        if( $resultCode != null &&  $resultCode ==  0){
           return $this->saveOTP($code, $phone);
        }else{
            return $result;
        }
    }

    public function sendSms($phone)
    {
        $client = new Client();
        $sender = 'wemoove';
        $apiKey = env('TERMI_API_KEY');
        $baseUrl = env('TERMI_BASE_URL');

        $data = [
            "api_key" => $apiKey,
            "message_type" => "NUMERIC",
            "to" => $phone,
            "from" => $sender,
            "channel" => "dnd",
            "pin_attempts" => 2,
            "pin_time_to_live" => 0,
            "pin_length" => 4,
            "pin_placeholder" => "< 1234 >",
            "message_text" => "Your wemoove confirmation pin is < 1234 >",
            "pin_type" => "NUMERIC"
        ];

        $response = $client->post("$baseUrl https://termii.com/api/sms/otp/send", [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $data
        ]);

        return $response->getBody()->getContents();
    }

    public function SendOtp($phone){

        $sender = 'wemoove';
        $apiKey = env('TERMI_API_KEY');
        $baseUrl = env('TERMI_BASE_URL');

            $data = [
                "api_key" => $apiKey,
                "message_type" => "NUMERIC",
                "to" => $phone,
                "from" => $sender,
                "channel" => "dnd",
                "pin_attempts" => 10,
                "pin_time_to_live" => 5,
                "pin_length" => 6,
                "pin_placeholder" => "< 1234 >",
                "message_text" => "Your pin is < 1234 >",
                "pin_type" => "NUMERIC"
            ];

            $postData = json_encode($data);

            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => "$baseUrl/api/sms/otp/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $postData,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json"
                ],
            ]);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            }

            curl_close($ch);

            if (isset($error_msg)) {
                return $error_msg;
            }

            return $response;
}

    public function saveOTP($code, $phone){
        return $otp = Otp::create([
            'phone_number' => $phone,
            'otp' => $code
        ]);
    }

    public function generateOtpCode()
    {
        $otpCode = mt_rand(1000, 9999);
        return $otpCode;
    }

    public function getLatestOtp($phone){
        return  Otp::where('phone_number', $phone)->latest()->first();
    }

    public function allOtpsByUser($phone){
        return Otp::where('phone_number', $phone)->get();
    }

    public function allOtps(){
        return Otp::all();
    }

    public function sendSmartSms($to, $otp){

        $templateCode = 5507718828;
        $appNameCode = 4523918228;

        $client = new Client();
        $apiToken = env ('SMARTSMS_API_TOKEN');
        $refId = uniqid();
        $senderId = 'wemoove';
        $options = [
            'multipart' => [
                [
                    'name' => 'token',
                    'contents' => $apiToken
                ],
                [
                    'name' => 'sender',
                    'contents' => $senderId
                ],
                [
                    'name' => 'phone',
                    'contents' => $to
                ],
                [
                    'name' => 'otp',
                    'contents' => $otp
                ],
                [
                    'name'=> 'app_name_code',
                    'contents'=>$appNameCode
                ],
                [
                    'name' => 'template_code',
                    'contents' => $templateCode
                ],
                [
                    'name' => 'ref_id',
                    'contents' => $refId
                ],
            ]];

        $url = env('SMARTSMS_BASEURL')."/io/api/client/v1/smsotp/send/";

        $request = new \GuzzleHttp\Psr7\Request('POST', $url);
        $res = $client->sendAsync($request, $options)->wait();
        return json_decode($res->getBody()->getContents());
    }

}
