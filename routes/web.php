<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web','auth']], function () {

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/airport/list', 'AirportController@index');
    Route::get('/airport/create', 'AirportController@create');
    Route::post('/airport/create', 'AirportController@store')->name('addPort');

    Route::get('/airline/list', 'AirlineController@index');
    Route::get('/airline/create', 'AirlineController@create');
    Route::post('/airline/create', 'AirlineController@store')->name('addAirline');

    Route::get('/route/list', 'RouteController@index');
    Route::get('/route/create', 'RouteController@create');
    Route::post('/route/create', 'RouteController@store')->name('addRoute');


    Route::get('/schedule/list', 'ScheduleController@index');
    Route::get('/schedule/create', 'ScheduleController@create')->name('createSchedule');
    Route::post('/schedule/create', 'ScheduleController@store')->name('uploadSchedule');
    
});



Auth::routes();

