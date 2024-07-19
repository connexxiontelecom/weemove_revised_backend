<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class RideController extends Controller
{

    public function __construct()
    {
        $this->ride = new Ride();
    }

    public function createRide(Request $request)
    {

        $this->validate($request, [
            'driver_id' => 'required',
            'ride_id' => 'required',
            'passenger_id' => 'required',
            'pickup' => 'required',
            'destination' => 'required',
        ]);

        $parameters = $request->all();
        $result = $this->ride->getRideById($parameters['ride_id']);

        if ($result != null) {
            return response()->json(['message' => "This ride has been accepted already by another Driver"], 400);
        } else {

            $_result = $this->ride->createRide($parameters);
            if ($_result instanceof Ride) {
                return response()->json(['ride' => $_result, 'message' => 'Ride accepted successfully'], 200);
            }

            return response()->json(['message' => "Accepting ride failed!"], 400);
        }

    }

    public function updateRide(Request $request)
    {

        $this->validate($request, [
            'ride_id' => 'required',
            'status' => 'required',
        ]);

        $parameters = $request->all();
        $result = $this->ride->updateRideStatus($parameters);

        if($result instanceof Ride){
            return response()->json(['ride'=> $result, 'message'=>'Success'], 200);
        }
        return response()->json(['message' => "Updating ride information failed!, {$result}"], 400);
    }


    public function getRides($id)
    {
        $result = $this->ride->getAllRidesByUser($id);

        if(!is_null($result)){
            return response()->json(['rides'=> $result, 'message'=>'Success'], 200);
        }
        return response()->json(['message' => "Error Fetching Rides information $result "], 400);

    }

    public function getRideDetails($id)
    {
        $result = $this->ride->getRideDetails($id);

        if($result instanceof Ride){
            return response()->json(['ride'=> $result, 'message'=>'Success'], 200);
        }
        return response()->json(['message' => "Updating ride information failed!, {$result}"], 400);
    }



}
