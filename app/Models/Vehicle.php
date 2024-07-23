<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{

    public function createVehicle(array $parameters, $picture)
    {
        try {
            $vehicle = new Vehicle();
            $vehicle->user_id = $parameters['user_id'];
            $vehicle->driver_id = $parameters['driver_id'];
            $vehicle->plate_number = $parameters['plate_number'];
            $vehicle->brand = $parameters['brand'];
            $vehicle->colour = $parameters['colour'];
            $vehicle->year = $parameters['year'];

            if (!empty($picture)) {
                $extension = $picture->getClientOriginalExtension();
                $dir = 'assets/uploads/images/';
                $picture_filename = uniqid() . '_' . time() . '_' . date('Ymd') . '.' . $extension;
                $picture->move($this->public_path($dir), $picture_filename);
                $vehicle->picture  = $picture_filename;
            }

            $vehicle->save();
            $vehicle->picture = url("/assets/uploads/images/" . $vehicle->picture);

            return $vehicle;

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getVehicleByDriverId($driverId){
        $vehicle =  Vehicle::where('driver_id', $driverId)->first();
        if(!is_null($vehicle)){
            $vehicle->picture = url("/assets/uploads/images/" . $vehicle->picture);
            return $vehicle;
        }
       return null;
    }

    public function getVehicleById($id){
        $vehicle =  Vehicle::where('id', $id)->first();
        $vehicle->picture = url("/assets/uploads/images/" . $vehicle->picture);
        return $vehicle;
    }

    public function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }

}
