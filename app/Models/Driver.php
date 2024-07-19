<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model{

    public function createDriver(array $parameters, $licenseFile)
    {
        try {
            $driver = new Driver;
            $driver->user_id = $parameters['user_id'];
            $driver->national_identification_number = $parameters['nin'];
            $driver->id_document = $parameters['id_document'];
            $driver->license_verified = $parameters['license_verified'] ?? 0;
            $driver->verified = $parameters['verified'] ?? 0;
            $driver->vehicle_id = $parameters['vehicle_id'] ?? 0;


            if (!empty($licenseFile)) {
                $extension = $licenseFile->getClientOriginalExtension();
                $dir = 'assets/uploads/license/';
                $license_filename = uniqid() . '_' . time() . '_' . date('Ymd') . '.' . $extension;
                $licenseFile->move($this->public_path($dir), $license_filename);
                $driver->license  = $license_filename;
            }

            $driver->save();

            $driver->license = url("/assets/uploads/license/" . $driver->license);

            return $driver;

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getDriverByUserId($userId)
    {
        $driver = Driver::where('user_id', $userId)->first();
        $user = new User;
        $vehicle = new Vehicle;
        if (!is_null($driver)) {
            $driver->license = url("/assets/uploads/license/" . $driver->license);
            $driver->user = $user->getUserById($driver->user_id);
            $driver->vehicle = $vehicle->getVehicleByDriverId($driver->id);
            return $driver;
        }
        return null;
    }

    public function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }


}
