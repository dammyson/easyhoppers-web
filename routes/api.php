<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');
 
Route::middleware('auth:api')->group(function () {
    Route::get('user', 'PassportController@details');
    Route::get('users', 'PassportController@users');
   //Route::resource('products', 'ProductController');

   Route::get('routes', 'RouteController@list');
   Route::get('airlines', 'AirlineController@list');
   Route::get('airports', 'AirportController@list');

   Route::get('schedules', 'API\ScheduleController@index');
   Route::put('schedule/update/{id}', 'API\ScheduleController@update');
   Route::post('schedule/departurePerformanceByDate', 'API\ScheduleController@departurePerformanceByDate');
   Route::post('schedule/departurePerformanceByDateAndTime', 'API\ScheduleController@departurePerformanceByDateAndTime');
   Route::post('schedule/arrivalPerformanceByDate', 'API\ScheduleController@arrivalPerformanceByDate');
   Route::post('schedule/arrivalPerformanceByDateAndTime', 'API\ScheduleController@arrivalPerformanceByDateAndTime');
   Route::post('schedule/cancelledFlightByDate', 'API\ScheduleController@cancelledFlightByDate');
   Route::post('schedule/cancelledFlightByDateAndTime', 'API\ScheduleController@cancelledFlightByDateAndTime');
   Route::post('schedule/delayedFlightByDate', 'API\ScheduleController@delayedFlightByDate');
   Route::post('schedule/delayedFlightByDateAndTime', 'API\ScheduleController@delayedFlightByDateAndTime');

   Route::post('/subscribe', 'API\OperationController@subscribe');

});
