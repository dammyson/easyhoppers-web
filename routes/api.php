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
Route::post('reset_password', 'PassportController@reset_password');
 
Route::group(['middleware' => ['web','auth:api']], function () {
  
});


Route::middleware('auth:api')->group(function () {
    Route::get('user', 'PassportController@details');
    Route::get('users', 'PassportController@users');
    Route::get('ischedules', 'PassportController@getSchedules');
    Route::post('/change_password', 'PassportController@change_password');
    //Route::resource('products', 'ProductController');

    Route::put('user/update', 'HomeController@update_user');
    
//    Route::get('routes', 'RouteController@list');
//    Route::get('airlines', 'AirlineController@list');
//    Route::get('airports', 'AirPortController@list');

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

   Route::post('/performanceAggregation', 'API\OperationController@performanceAggregation');
   Route::post('/genericPerformance', 'API\OperationController@genericPerformance');
   Route::post('/getAirlinePerformanceComparism', 'API\OperationController@getAirlinePerformanceComparism');


   Route::get('/airlines', 'API\OperationController@airline_list');
   Route::get('/airports', 'API\OperationController@airport_list');
   Route::get('/routes', 'API\OperationController@route_list');


   Route::get('/getSchedule/{id}', 'API\ScheduleController@get');

   Route::post('/expense/create', 'API\ExpenseController@create_expense');
   Route::get('/expense', 'API\ExpenseController@all_expense');
   Route::get('/expense/{id}', 'API\ExpenseController@expense_details');
   Route::post('/expense/add/details', 'API\ExpenseController@add_expense_details');
   Route::get('/expense/close/{id}', 'API\ExpenseController@close_expense');
   Route::post('/expense/send_expense', 'API\ExpenseController@send_expense');

   
   Route::post('/graph/', 'API\GraphController@graph');
});
