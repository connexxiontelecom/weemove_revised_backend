<?php

namespace App\Http\Controllers;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{

    public function __construct(){
        $this->vehicle = new Vehicle();
    }

    public function createVehicle (Request $request){

        $this->validate($request, [
            'user_id' => 'required',
            'driver_id' => 'required',
            'brand' => 'required',
            'colour' => 'required',
            'year' => 'required',
            'picture' => 'required',
            'plate_number' => 'required',
        ]);

        $parameters =  $request->all();
        $pictureFile = $request->file('picture');
        $result = $this->vehicle->createVehicle($parameters, $pictureFile);

        if($result instanceof Vehicle){
            return response()->json(['vehicle'=> $result, 'message'=>'Vehicle information updated  Successfully'], 200);
        }
        return response()->json(['message' => "Updating vehicle information failed!, {$result}"], 400);

    }

}
