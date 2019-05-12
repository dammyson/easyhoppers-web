<?php

namespace App\Http\Controllers;

use App\AirPort;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AirPortController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $airports = Airport::all();
        return view('airport.index',['airports'=>$airports]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('airport.create');
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
            'name' => 'required|min:3',
            'code' => 'required|min:3'
        ]);

        $error = $validator->errors()->first();
        if($validator->fails()){
            Session::flash('error', $error);
            return back();
        }

        $airline = AirPort::create(['name' => $request->name, 'code' => $request->code,'description' => $request->description]);
        Session::flash('success', 'Airport added successfully !!!');

        $airports = AirPort::all();
        return view('airport.index',['airports'=>$airports]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AirPort  $airPort
     * @return \Illuminate\Http\Response
     */
    public function show(AirPort $airPort)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AirPort  $airPort
     * @return \Illuminate\Http\Response
     */
    public function edit(AirPort $airPort)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AirPort  $airPort
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AirPort $airPort)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AirPort  $airPort
     * @return \Illuminate\Http\Response
     */
    public function destroy(AirPort $airPort)
    {
        //
    }

    //abeg i no fit create another controller just to list airport cuz say na API i go just chuk am inside here
    public function list(){
        $airport = AirPort::all();
        return response()->json(['message' => $airport, 'status' => true ], 200);
    }
}
