<?php

namespace App\Http\Controllers;

use App\Airline;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AirlineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $airlines = Airline::all();
       return view('airline.index',['airlines'=>$airlines]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('airline.create');
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

        $airline = Airline::create(['name' => $request->name, 'code' => $request->code,'description' => $request->description]);
        Session::flash('success', 'Airline added successfully !!!');

        $airlines = Airline::all();
       return view('airline.index',['airlines'=>$airlines]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Airline  $airline
     * @return \Illuminate\Http\Response
     */
    public function show(Airline $airline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Airline  $airline
     * @return \Illuminate\Http\Response
     */
    public function edit(Airline $airline)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Airline  $airline
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Airline $airline)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Airline  $airline
     * @return \Illuminate\Http\Response
     */
    public function destroy(Airline $airline)
    {
        //
    }

    //abeg i no fit create another controller just to list airlines cuz say na API i go just chuk am inside here
    public function list(){
        $airline = Airline::all();
        return response()->json(['message' => $airline, 'status' => true ], 200);
    }
}
