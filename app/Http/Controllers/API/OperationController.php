<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $validator = \Validator::make($request->all(), [
            'route_id' => 'required',
            'airlineCode' => 'required',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }
        
        $schedule = Schedule::where('route_id',$request->route_id)->where('airlineCode',$request->airline)->get();
        $percentageArrivals = self::percentageArrivals( $schedule );
        $percentageDepartures = self::percentageDepartures( $schedule );
        $percentageCancellations = self::percentageCancellations( $schedule );
        $percentageDelayed = self::percentageDelayed( $schedule );

        return response()->json(['percentageArrivals' =>  $percentageArrivals,'percentageDepartures' =>  $percentageDepartures, 'percentageDelayed' => $percentageDelayed,'percentageCancellations' =>  $percentageCancellations , 'status' => true ], 200);

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

        Schedule::where('airline',)->get();

    }
}
