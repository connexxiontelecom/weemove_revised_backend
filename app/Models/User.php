<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use App\Models\Permissions;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];

    public function createUser(array $parameters)
    {
        try {
            $user = new User;
            $user->fullname = $parameters['full_name'];
            $user->email = $parameters['email'] ?? null;
            $user->phone_number = $parameters['phone_number'];
            $user->password = app('hash')->make($parameters['phone_number']);
            $user->uuid = uniqid();
            $user->save();
            return $user;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function isExist($phone)
    {
        $exists = User::where("phone_number", $phone)->first();
        if ($exists != null) {
            return true;
        } else {
            return false;
        }
    }


    public function getUserById($id)
    {
        return User::where("id", $id)->first();
    }


    public function allUsers(){
        $users = User::all();
        return $users;
    }


}
