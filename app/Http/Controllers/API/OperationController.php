<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ViewModel\ScheduleVM;

class OperationController extends Controller
{
    //
    public function subscribe(Request $request){
        // $sus = $request->user();
        // return response()->json(['message' => $sus, 'status' => false ], 200);

        $validator = \Validator::make($request->all(), [
            'email' => 'required',
            'schedule_id' => 'required',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }
        if($schedule = Schedule::where('id',$request->schedule_id)->first()){
            $user = User::where('email',$request->email)->first();

            $str_arr = explode (",", $user->subscription);
            
            if(array_key_exists($request->schedule_id, $str_arr)) {
                return response()->json(['message' => 'User already subscribed','status' => false ], 200);
            }

            $user->subscription = $user->subscription.$request->schedule_id.',';
            if($user->save()){
                return response()->json(['message' => 'Successful','status' => true ], 200);
            }  
            return response()->json(['message' => 'Failed','status' => false ], 200);
        }
        return response()->json(['message' => 'Invalid schedule id','status' => false ], 200);
    }

    public function performanceAggregation(Request $request){

        $items = array();
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
        $itt = 0;
        //$schedules = Schedule::whereBetween('scheduled_departure_date',[$weekStartDate, $weekEndDate])->get();
        $schGrps = DB::select("select airlineCode, route_id FROM schedules s  where s.scheduled_departure_date between '$weekStartDate' and '$weekEndDate' group by airlineCode, route_id "); 
        
        foreach ($schGrps as $key => $schGrp) {
            
           
            $schedules = Schedule::where('route_id',$schGrp->route_id)->where('airlineCode',$schGrp->airlineCode)->get();
            if($schedules){

                    $percentageArrivals = self::percentageArrivals( $schedules );
                    $percentageDepartures = self::percentageDepartures( $schedules );
                    $percentageCancellations = self::percentageCancellations( $schedules );
                    $percentageDelayed = self::percentageDelayed( $schedules );
                    
                   
                    $firstSchedule = $schedules->first();
                   
                    $flightObj =  new ScheduleVM();
                    $flightObj->percentageArrivals = $percentageArrivals;
                    $flightObj->percentageDepartures = $percentageDepartures;
                    $flightObj->percentageCancellations = $percentageCancellations;
                    $flightObj->percentageDelayed = $percentageDelayed;
                    $flightObj->description = $firstSchedule['description'];
                    $flightObj->flight = $firstSchedule['airlineCode'];
                    $flightObj->airlineId = $firstSchedule['airlineCode'];
                    $flightObj->routeId = $firstSchedule['route_id'];
                    $flightObj->route = $firstSchedule['description'];
                    $flightObj->status = $firstSchedule['status'];
                   
                    array_push($items,$flightObj);
            }

        }
        return response()->json([ 'status' => true,'message' => 'Successful', 'data' => $items], 200);

       




        // $schedule = Schedule::where('route_id',$request->route_id)->where('airlineCode',$request->airline)->get();
        // $percentageArrivals = self::percentageArrivals( $schedule );
        // $percentageDepartures = self::percentageDepartures( $schedule );
        // $percentageCancellations = self::percentageCancellations( $schedule );
        // $percentageDelayed = self::percentageDelayed( $schedule );

        // return response()->json(['percentageArrivals' =>  $percentageArrivals,'percentageDepartures' =>  $percentageDepartures, 'percentageDelayed' => $percentageDelayed,'percentageCancellations' =>  $percentageCancellations , 'status' => true ], 200);

    }

    public function percentageArrivals($schedule){
        
        $totalArrivedSchedule = $schedule->count();
        $noOfArrivalsOnTime = 0;


        
        foreach ($schedule as $key => $value) {
            $diff_in_minutes = $value->scheduled_arrival_time->diffInMinutes($value->actual_arrival_time);
            if(($value->scheduled_arrival_time->diffInMinutes($value->actual_arrival_time)<5)){
                $noOfArrivalsOnTime++;
            }
        }
        if($totalArrivedSchedule == 0 ){
            return 0;
        }
        return $percentageArrivals = ($noOfArrivalsOnTime / $totalArrivedSchedule) * 100;
    }

    public function percentageDepartures($schedule){
        
        $totalDepartureSchedule = $schedule->count();
        $noOfDeparturesOnTime = 0;
        foreach ($schedule as $key => $value) {
            $diff_in_minutes = $value->scheduled_departure_time->diffInMinutes($value->actual_departure_time);
            if(($value->scheduled_departure_time->diffInMinutes($value->actual_departure_time)<5)){
                $noOfDeparturesOnTime++;
            }
        }
        if($totalDepartureSchedule == 0 ){
            return 0;
        }
        return $percentageDepartures = ($noOfDeparturesOnTime / $totalDepartureSchedule) * 100;
    }

    public function percentageCancellations($schedule){
        
        $totalArrivedSchedule = $schedule->count();
        $totalcancelledSchedules = $schedule->where('status','3')->count();
        if($totalArrivedSchedule == 0 ){
            return 0;
        }
        return $percentageCancellations = ($totalcancelledSchedules / $totalArrivedSchedule) * 100;
    }

    public function percentageDelayed($schedule){
        
        $totalArrivedSchedule = $schedule->count();
        $noOfArrivalsOnTime = 0;
        foreach ($schedule as $key => $value) {
            $diff_in_minutes = $value->scheduled_arrival_time->diffInMinutes($value->actual_arrival_time);
            if(($value->scheduled_arrival_time->diffInMinutes($value->actual_arrival_time)>5)){
                $noOfArrivalsOnTime++;
            }
        }
        if($totalArrivedSchedule == 0 ){
            return 0;
        }
        return $percentageArrivals = ($noOfArrivalsOnTime / $totalArrivedSchedule) * 100;
    }

    public function genericPerformance(Request $request){
        $validator = \Validator::make($request->all(), [
            'airlineCode' => 'required',
            'route_id' => 'required',
            'status' => 'required'
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }
        //initialize time variables
        $now = Carbon::now();
        $startDate = $now->startOfWeek()->format('Y-m-d H:i');
        $endDate = $now->endOfWeek()->format('Y-m-d H:i');
        $startTime = "00:00";
        $endTime = "23:59";

        $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and scheduled_departure_time between '$startTime' and '$endTime' and status = '$request->status' and route_id = '$request->route_id'  and airlineCode = '$request->airlineCode' "); 

        if($schedules && count($schedules) > 0){
            return response()->json(['status' => true, 'message' => 'successful', 'data'=> $schedules ], 200);
        }
        return response()->json([ 'status' => false,'message' => 'No records found'], 200);
    }  
    
    public function routesByAirline(Request $request){
        $validator = \Validator::make($request->all(), [
            'airlineCode' => 'required'
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

       // Schedule::where('airline',)->get();

    }
}
