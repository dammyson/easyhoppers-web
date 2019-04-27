<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Schedule;
use App\Events\ScheduleChanged;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = DB::select('select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id');
        if($schedules){
            if(count($schedules) > 0){
                return response()->json([
                    'status' => true,
                    'message' => 'successful',
                    'data'=> $schedules 
                ], 200);
            }
            return response()->json([
                'status' => false,
                'message' => 'No records found'
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $schedule = Schedule::where('id', $id)->first();
        if($schedule){
            return response()->json(['message' => 'No schedule found', 'status' => false ], 200);
        }
        if(!$msg_array = json_decode($request->getContent(), true)){
            return response()->json(['message' => 'Message body is empty', 'status' => false, 'as'=>$msg_array ], 200);
        };
        
        if(array_key_exists('route_id', $msg_array)) {
            if(!Route::find($request->route_id)){
                return response()->json(['message' => 'Invalid route id', 'status' => false ], 200);
            }
            $schedule->route_id =  $request->route_id;
        }
        if(array_key_exists('actual_departure_time', $msg_array)) {
            $schedule->actual_departure_time =  $request->actual_departure_time;
        }
        if(array_key_exists('actual_departure_date', $msg_array)) {
            $schedule->actual_departure_date =  $request->actual_arrival_date;
        }
        if(array_key_exists('actual_arrival_date', $msg_array)) {
            $schedule->actual_arrival_date =  $request->actual_arrival_date;
        }
        if(array_key_exists('actual_arrival_time', $msg_array)) {
            $schedule->actual_arrival_time =  $request->actual_arrival_time;
        }
        if(array_key_exists('status', $msg_array)) {
            $schedule->status =  $request->status;
        }

        if($schedule->save()){
            event(new ScheduleChanged($schedule));
        }

        return response()->json([
            'message' => 'Successful',
            'status' => true
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

    }
}
