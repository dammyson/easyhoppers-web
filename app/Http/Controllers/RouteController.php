<?php

namespace App\Http\Controllers;

use App\Route;
use Illuminate\Http\Request;
use App\AirPort;
use Session;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $routes = Route::all();
        return view('flightroute.index',['routes'=>$routes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $airportList = AirPort::pluck('name', 'code');
        return view('flightroute.create',compact('airportList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'departure_port' => 'required',
            'arrival_port' => 'required|different:departure_port'
        ]);

        $error = $validator->errors()->first();
        if($validator->fails()){
            Session::flash('error', $error);
            return back();
        }

        $prevRecord = Route::where('departure_port',$request->departure_port)->where('arrival_port',$request->arrival_port)->count();
        if($prevRecord > 0){
            Session::flash('error', 'Route already exits');
            return back();
        }
        $code = $request->departure_port ."-". $request->arrival_port;
        $departure_port = AirPort::where('code',$request->departure_port)->first();
        $arrival_port = AirPort::where('code',$request->arrival_port)->first();
        $airline = Route::create(['arrival_port' => $arrival_port->code, 'departure_port' => $departure_port->code,'code'=> $code]);
        Session::flash('success', 'Route added successfully !!!');

        $routes = Route::all();
        return view('flightroute.index',['routes'=>$routes]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function show(Route $route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function edit(Route $route)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Route $route)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function destroy(Route $route)
    {
        //
    }
}
