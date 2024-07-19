<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model{

    public function createRide(array $parameters)
    {
        try {
            $ride = new Ride;
            $ride->driver_id = $parameters['driver_id'];
            $ride->ride_id = $parameters['ride_id'];
            $ride->passenger_id = $parameters['passenger_id'];
            $ride->pickup = $parameters['pickup'];
            $ride->destination = $parameters['destination'];
            $ride->status = $parameters['status'] ?? 0;
            $ride->save();
            return $ride;

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getRideById($rideId){
       $ride = Ride::where('ride_id', $rideId)->first();
       return $ride;
    }

    public function updateRideStatus(array $parameters)
    {
        $rideId = $parameters['ride_id'];
        $status = $parameters['status'];
        try {
            $ride = $this->getRideById($rideId);
            if (!$ride) {
                return false;
            };
            $ride->status = $status;
            $ride->save();
            return  $ride;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllRidesByUser($id)
    {
        try {

            $rides = Ride::where('passenger_id', $id)->orWhere('driver_id', $id)->get();
            $driver = new Driver();
            $user = new User();

            foreach ($rides as $ride){
                $ride->driver = $driver->getDriverByUserId($ride->driver_id);
                $ride->passenger = $user->getUserById($ride->passenger_id);
            }

            return $rides;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getRideDetails($rideId){

        $ride = $this->getRideById($rideId);

        $user = new User;
        $driver = new Driver;
        $vehicle = new Vehicle;

        $passenger  =  $user->getUserById($ride->passenger_id);
        $driverDetails = $driver->getDriverByUserId($ride->driver_id);
        $vehicleDetails = $vehicle->getVehicleByDriverId($driverDetails->id);

        $ride->passenger =  $passenger;
        $ride->driver = $driverDetails;
        $ride->vehicle = $vehicleDetails;

        return $ride;

    }



}
