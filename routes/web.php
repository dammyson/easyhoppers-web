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

    Route::get('/airport/list', 'AirPortController@index');
    Route::get('/airport/create', 'AirPortController@create');
    Route::post('/airport/create', 'AirPortController@store')->name('addPort');

    Route::get('/airline/list', 'AirlineController@index');
    Route::get('/airline/create', 'AirlineController@create');
    Route::post('/airline/create', 'AirlineController@store')->name('addAirline');

    Route::get('/route/list', 'RouteController@index');
    Route::get('/route/create', 'RouteController@create');
    Route::post('/route/create', 'RouteController@store')->name('addRoute');


    Route::get('/schedule/list', 'ScheduleController@index');
    Route::get('/schedule/create', 'ScheduleController@create')->name('createSchedule');
    Route::post('/schedule/create', 'ScheduleController@store')->name('uploadSchedule');
    Route::get('/schedule/listing', 'ScheduleController@schedule_list');

    Route::get('/profile', 'HomeController@profile');
    Route::get('/user/details/{id}', 'HomeController@user_details');
    Route::get('/users/list/{filter?}', 'HomeController@list')->name('userListing');
    Route::get('/user/new', 'HomeController@create');
    Route::post('/user/new', 'HomeController@store')->name('uploadUsers');
    Route::post('/user/single/new', 'HomeController@save')->name('saveUser');
    Route::get('/user/status/{user_id}/{status}', 'HomeController@change_agent_status');
    Route::get('/user/delete/{id}', 'HomeController@delete_agent');


    Route::get('/terminal/new', 'TerminalController@create');
    Route::post('/terminal/new', 'TerminalController@store')->name('saveTerminal');
    Route::get('/terminal/list', 'TerminalController@index');
    Route::get('/terminal/load_terminals/{state_id}', 'TerminalController@load_terminals');
    

});



Auth::routes();

