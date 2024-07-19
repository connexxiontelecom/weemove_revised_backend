<?php

namespace App\Http\Controllers;

use App\Auth;
use App\Models\User;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    private Auth\JwtAuthServices $jwtService;

    public function __construct()
    {
        $this->user = new User();
        $this->driver = new Driver();
        $this->vehicle = new Vehicle();
    }


    public function createUser(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required',
            'phone_number' => 'required',
            'email' => 'nullable|email',
        ]);
        $exists = $this->user->isExist($request->input('phone_number'));
        if ($exists) {
            return response()->json([ 'message'=> 'User with ID exists already'], 400);
        } else {
            $parameters = $request->all();
            $result = $this->user->createUser($parameters);
            if($result instanceof User){
                $this->jwtService = new Auth\JwtAuthServices();
                $token = $this->jwtService->init($result->uuid, $result->phone_number);
                return response()->json(['user'=> $result, "token"=>$token, 'message'=>'Profile created Successfully'], 200);
            }
            return response()->json(['message' => "User Registration Failed!, {$result}"], 400);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('phone_number');

        $response = $this->attemptLogin($credentials["phone_number"]);
        if ($response != false) {
            return response()->json(["user"=>$response['user'], "token"=>$response['token'], "driver"=>$response['driver'], "vehicle"=>$response['vehicle']   ,"message"=>'success'], 200);
        } else {
            return response()->json([ "message"=>'Login Failed!, Incorrect credentials'], 400);
        }
    }

    public function attemptLogin($phone_number)
    {
        $user = User::where('phone_number', $phone_number)->first();
        if (!is_null($user) && Hash::check($phone_number, $user->password)) {
            $this->jwtService = new Auth\JwtAuthServices();
            $token = $this->jwtService->init($user->uuid, $phone_number);
            $driverStatus =  $this->getUserDriverStatus($user->id);
            return ["user" => $user, "token" => $token] +  $driverStatus;

        } else {
            return false;
        }
    }

    public function  allUsers(){
       $response =  $this->user->allUsers();
       return response()->json($response, 200);
    }

    public function getUserDriverStatus($userId){
        $driver = $this->driver->getDriverByUserId($userId);

        if($driver != null){
            $vehicle = $this->getDriverVehicleInfo($driver->id);
            return ["driver" => $driver, "vehicle" => $vehicle];
        }
        else{
            return ["driver" => null, "vehicle" => null];
        }
    }


    public function getDriverVehicleInfo($driverId){
        return $this->vehicle->getVehicleByDriverId($driverId);
    }


}
