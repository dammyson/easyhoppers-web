<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

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
            
            if(array_key_exists('subscription', $request->schedule_id)) {
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

        return response()->json(['data' => [ $percentageArrivals,$percentageDepartures, $percentageDelayed, $percentageCancellations ], 'status' => true ], 200);

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
        $percentageArrivals = ($noOfArrivalsOnTime / $totalArrivedSchedule) * 100;
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
        $percentageDepartures = ($noOfDeparturesOnTime / $totalDepartureSchedule) * 100;
    }

    public function percentageCancellations($schedule){
        
        $totalArrivedSchedule = $schedule->count();
        $totalcancelledSchedules = $schedule->where('status','3')->count();
        
        $percentageCancellations = ($totalcancelledSchedules / $totalArrivedSchedule) * 100;
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
        $percentageArrivals = ($noOfArrivalsOnTime / $totalArrivedSchedule) * 100;
    }
}
