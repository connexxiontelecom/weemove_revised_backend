<?php

namespace App\Http\Controllers;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{

    public function __construct(){
        $this->driver = new Driver();
    }

    public function createDriver (Request $request){

        $this->validate($request, [
            'user_id' => 'required',
            'nin' => 'required',
            'license' => 'required',
            'id_document' => 'required',
        ]);

        $parameters =  $request->all();
        $licenseFile = $request->file('license');
        $result = $this->driver->createDriver($parameters, $licenseFile);
        if($result instanceof Driver){
            return response()->json(['driver'=> $result, 'message'=>'Driver information updated  Successfully'], 200);
        }
        return response()->json(['message' => "Updating driver information failed!, {$result}"], 400);

    }

}
