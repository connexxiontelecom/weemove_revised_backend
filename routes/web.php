<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


// Authenticated API route group
$router->group(['middleware' => ['jwt.auth'], 'prefix'=>'api/auth' ], function () use ($router) {



});


/* Unauthenticated Routes */
$router->group(['prefix'=>'api'], function () use ($router) {
    $router->post('user/create', 'AuthController@createUser');
    $router->post('user/login', 'AuthController@login');
    $router->post('otp/send', 'OtpController@sendOtp');


    // DRIVER
    $router->post('driver/create', 'DriverController@createDriver');

    // VEHICLE
    $router->post('vehicle/create', 'VehicleController@createVehicle');

    // RIDES
    $router->post('ride/create', 'RideController@createRide');
    $router->get('ride/{id}', 'RideController@getRides');
    $router->put('ride/update', 'RideController@updateRide');
    $router->get('ride/details/{id}', 'RideController@getRideDetails');


    //WALLET

    $router->post('wallet/create', 'WalletController@createVirtualAccount');











});
