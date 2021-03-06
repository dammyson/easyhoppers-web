<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;
use App\Schedule;
use App\Route;
use App\AirPort;
use App\Airline;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ViewModel\ScheduleVM;
use App\Http\Controllers\Controller;

class OperationController extends Controller
{
    //
    public function subscribe(Request $request){


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
            'from' => 'required',
            'to' => 'required',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

        $items = array();

        $weekStartDate = $request->from;
        $weekEndDate = $request->to;

        $schGrps = DB::select("select airlineCode, route_id FROM schedules s  where s.scheduled_departure_date between '$weekStartDate' and '$weekEndDate' group by airlineCode, route_id "); 
       
        foreach ($schGrps as $key => $schGrp) {
            
           
            $schedules = Schedule::where('route_id',$schGrp->route_id)->where('airlineCode',$schGrp->airlineCode)->whereBetween('scheduled_departure_date', [$weekStartDate, $weekEndDate])->get();
            $firstSchedule = DB::select("select s.id,al.code airCode,s.airlineCode ,al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.code, r.departure_port, r.arrival_port, r.departure_port_name, r.arrival_port_name, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$weekStartDate' and '$weekEndDate' and  s.route_id = '$schGrp->route_id' and  s.airlineCode  = '$schGrp->airlineCode' ");
            if($schedules){
                
                $total_schedule_count = $schedules->count();

                $percentageOnTimeArrivals = self::percentageOnTimeArrivals( $schedules, $total_schedule_count );
                $percentageOnTimeDepartures = self::percentageOnTimeDepartures( $schedules, $total_schedule_count );
                $percentageCancellations = self::percentageCancellations( $schedules, $total_schedule_count );
                $percentageDelayedArrivals = self::percentageDelayedArrivals( $schedules, $total_schedule_count );
                $percentageDelayedDepartures = self::percentageDelayedDepartures( $schedules, $total_schedule_count );
                $percentageEarlyDepartures = self::percentageEarlyDepartures( $schedules, $total_schedule_count );
                $percentageEarlyArrival= self::percentageEarlyArrival( $schedules, $total_schedule_count );
                
                $route = Route::select('id','departure_port_name','arrival_port_name', 'code')->where('id', $schGrp->route_id)->first();

                $flightObj =  new \stdClass;
                $flightObj->percentageOnTimeArrivals = round($percentageOnTimeArrivals,2);
                $flightObj->percentageOnTimeDepartures = round($percentageOnTimeDepartures,2);
                $flightObj->percentageCancellations = round($percentageCancellations,2);
                $flightObj->percentageDelayedArrivals = round($percentageDelayedArrivals,2);
                $flightObj->percentageDelayedDepartures = round($percentageDelayedDepartures,2);
                $flightObj->percentageEarlyDepartures = round($percentageEarlyDepartures,2);
                $flightObj->percentageEarlyArrival = round($percentageEarlyArrival,2);
                $flightObj->description = $firstSchedule[0]->description;
                $flightObj->flight = $firstSchedule[0]->name;
                $flightObj->airlineId = $schGrp->airlineCode;
                $flightObj->routeId = $schGrp->route_id;
                $flightObj->route = $route['code'];
                $flightObj->departure_port_name = $route['departure_port_name'];
                $flightObj->arrival_port_name = $route['arrival_port_name'];
                
                array_push($items,$flightObj);

            
            }

        }
        return response()->json([ 'status' => true,'message' => 'Successful', 'data' => $items], 200);

    }

    public function percentageOnTimeArrivals($schedule, $totalArrivedSchedule){
        
        $noOfArrivalsOnTime = 0;


        $noOfArrivalsOnTime = $schedule->where('status','1')->count(); 
       
        if($noOfArrivalsOnTime == 0 ){
            return 0;
        }
        return  ($noOfArrivalsOnTime / $totalArrivedSchedule) * 100;
    }

    public function percentageOnTimeDepartures($schedule, $totalDepartureSchedule){

        $noOfDeparturesOnTime = 0;

        $noOfDeparturesOnTime = $schedule->where('status','2')->count(); 

        if($noOfDeparturesOnTime == 0 ){
            return 0;
        }
        return  ($noOfDeparturesOnTime / $totalDepartureSchedule) * 100;
    }

    public function percentageCancellations($schedule, $totalArrivedSchedule){
        
        $totalcancelledSchedules = $schedule->where('status','5')->count();
        if($totalcancelledSchedules == 0 ){
            return 0;
        }
        return ($totalcancelledSchedules / $totalArrivedSchedule) * 100;
    }

    public function percentageDelayedArrivals($schedule, $totalArrivedSchedule){

        $totalDelayedSchedule = $schedule->where('status','3')->count();
        if($totalDelayedSchedule == 0 ){
            return 0;
        }
        return ($totalDelayedSchedule / $totalArrivedSchedule) * 100;
    }

    public function percentageDelayedDepartures($schedule, $totalArrivedSchedule){
        
        $totalDelayedSchedule = $schedule->where('status','4')->count();
        if($totalDelayedSchedule == 0 ){
            return 0;
        }
        return ($totalDelayedSchedule / $totalArrivedSchedule) * 100;
    }

    public function percentageRescheduled($schedule, $totalArrivedSchedule){
        
        $totalRescheduled = $schedule->where('status','6')->count();
        if($totalRescheduled == 0 ){
            return 0;
        }
        return ($totalRescheduled / $totalArrivedSchedule) * 100;
    }

    public function percentageEarlyArrival($schedule, $totalArrivedSchedule){

        $totalRescheduled = $schedule->where('status','11')->count();
        if($totalRescheduled == 0 ){
            return 0;
        }
        return ($totalRescheduled / $totalArrivedSchedule) * 100;
    }

    public function percentageEarlyDepartures($schedule, $totalArrivedSchedule){
        
        $totalRescheduled = $schedule->where('status','12')->count();
        if($totalRescheduled == 0 ){
            return 0;
        }
        return ($totalRescheduled / $totalArrivedSchedule) * 100;
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

        $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, r.departure_port_name, r.arrival_port_name, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and scheduled_departure_time between '$startTime' and '$endTime' and status = '$request->status' and route_id = '$request->route_id'  and airlineCode = '$request->airlineCode' "); 

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

    public function percentage_delayed_arrival($schedules){
        $totalSchedules = $schedules->count();
        $noOfArrivalsOnTime = 0;


    }

    public function getAirlinePerformanceComparism(Request $request){

        $validator = \Validator::make($request->all(), [
            'route_id' => 'required',
            'status' => 'required',
            'from' => 'required',
            'to' => 'required'
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

       $result = DB::select('call get_flight_performance(?,?,?,?)',array($request->route_id,$request->status, $request->from, $request->to));
        if($result){
            $iret = array();
            foreach ($result as $key => $value) {
                $iData = new \stdClass;
                $iData->airline = $value->airline;
                $iData->percentage =  (float)$value->percentage;

                array_push($iret, $iData);
            }

            return response()->json([ 'status' => true,'message' => 'Successful', 'data' => $iret], 200);
        }
        return response()->json([ 'false' => true,'message' => 'No results'], 200);
    }

    public function airline_list(){
        $airline = Airline::all();
        if($airline->count() > 0){
            $iret = array();
            foreach ($airline as $key => $value) {
                $iData = new \stdClass;
                $iData->Id = (int)$value->id;
                $iData->Name =  $value->name;
                $iData->Value = (string)$value->id;

                array_push($iret, $iData);
            }
            return response()->json(['message' => $airline, 'status' => true, 'data' =>$iret ], 200);
        }
        return response()->json(['message' => $airline, 'status' => true ], 200);
    }

    public function airport_list(){
        $airport = AirPort::all();
        return response()->json(['message' => $airport, 'status' => true ], 200);
    }

    public function route_list(){
        $routes = Route::orderBy('code', 'asc')->get();
        if($routes->count() > 0){
            $iret = array();
            foreach ($routes as $key => $value) {
                $iData = new \stdClass;
                $iData->Id = (int)$value->id;
                $iData->Name =  $value->code;
                $iData->Value = (string)$value->id;

                array_push($iret, $iData);
            }
            return response()->json(['message' => $routes, 'status' => true, 'data' =>$iret ], 200);
        }
        return response()->json(['message' => $routes, 'status' => true ], 200);
    }
}
