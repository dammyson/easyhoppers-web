<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Schedule;
use App\Events\ScheduleChanged;
use Carbon\Carbon;
use App\User;
use App\ViewModel\ScheduleVM;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authUser = auth()->user();
        $items = array();
        $isSubscribed = false;
        try{
            $now = Carbon::now();
            $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
            $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
            $schedules = DB::select("select s.id, al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$weekStartDate' and '$weekEndDate' ");
            if($schedules){
                if(count($schedules) > 0){
                    foreach ($schedules as $key => $schedule) {
                        $user = User::where('email',$authUser->email)->first();
                        //return response()->json(['status' => true, 'message' => 'successful', 'data'=> $user ], 200);
                        if($user->subscription != "" || $user->subscription != null){
                            $subscription_arr = explode (",", $user->subscription);
                            if(in_array($schedule->id, $subscription_arr)){
                                $isSubscribed = true;
                            }
                        }
                        $iSchedule = new ScheduleVM();
                        $iSchedule->id = $schedule->id;
                        $iSchedule->name = $schedule->name;
                        $iSchedule->description = $schedule->id;
                        $iSchedule->scheduled_departure_time = $schedule->scheduled_departure_time;
                        $iSchedule->scheduled_arrival_time = $schedule->scheduled_arrival_time;
                        $iSchedule->departure_port = $schedule->departure_port;
                        $iSchedule->arrival_port = $schedule->arrival_port;
                        $iSchedule->status = $schedule->status;
                        $iSchedule->scheduled_departure_date = $schedule->scheduled_departure_date;
                        $iSchedule->scheduled_arrival_date = $schedule->scheduled_arrival_date;
                        $iSchedule->isSubscribed = $isSubscribed;
                        array_push($items,$iSchedule);
                        //$items->push($iSchedule);
                    }
                    return response()->json(['status' => true, 'message' => 'successful', 'data'=> $items ], 200);
                }
                return response()->json([ 'status' => false,'message' => 'No records found'], 200);
            }
            return response()->json([ 'status' => false,'message' => 'No schedules for this week'], 200);
        }catch(\Exception $ex){
            return response()->json([ 'status' => false,'message' => $ex->getMessage()], 200);
        }
    }

    public function get($id)
    {
        if($id == ""){
            return response()->json([ 'status' => false,'message' => "No Schedule Id"], 200);
        }
        $schedules = DB::select("select s.id, al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.id = '$id' ");
        if($schedules == null || count($schedules) < 1){
            return response()->json([ 'status' => false,'message' => "No schedule found"], 200);
        }else{
            return response()->json([ 'status' => true,'message' => $schedules], 200);
        }
    }

    public function departurePerformanceByDate(Request $request){
        $validator = \Validator::make($request->all(), [
            'route' => 'required_without:airline',
            'airline' => 'required_without:route',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

        $startDate= "";
        $endDate = ""; 
        if($startDate = !$request->startDate || $endDate = !$request->endDate){
            $now = Carbon::now();
            $startDate = $now->startOfWeek()->format('Y-m-d H:i');
            $endDate = $now->endOfWeek()->format('Y-m-d H:i');
        }
        $schedules="";
        if($request->route){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and status = '4' and route_id = '$request->route_id' ");
            
        }else if($request->airline){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and status = '4' and airlineCode = '$request->airline' ");
        }
        if($schedules && count($schedules) > 0){
                return response()->json(['status' => true, 'message' => 'successful', 'data'=> $schedules ], 200);
        }
        return response()->json([ 'status' => false,'message' => 'No records found'], 200);
    }

    public function departurePerformanceByDateAndTime(Request $request){
        $validator = \Validator::make($request->all(), [
            'route_id' => 'required_without:airline',
            'airline' => 'required_without:route',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

        $startDate= "";
        $endDate = ""; 
        $startTime= "";
        $endTime = ""; 
        if($startDate = !$request->startDate || $endDate = !$request->endDate){
            $now = Carbon::now();
            $startDate = $now->startOfWeek()->format('Y-m-d H:i');
            $endDate = $now->endOfWeek()->format('Y-m-d H:i');
            $startTime = "00:00";
            $endTime = "23:59";
        }
        $schedules="";
        if($request->route){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and scheduled_departure_time between '$startTime' and '$endTime' and status = '4' and route_id = '$request->route_id'"); 
        }else if($request->airline){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and scheduled_departure_time between '$startTime' and '$endTime' and status = '4' and airlineCode = '$request->airline' ");
        }
        if($schedules && count($schedules) > 0){
            return response()->json(['status' => true, 'message' => 'successful', 'data'=> $schedules ], 200);
        }
        return response()->json([ 'status' => false,'message' => 'No records found'], 200);
    }

    public function arrivalPerformanceByDate(Request $request){
        $validator = \Validator::make($request->all(), [
            'route' => 'required_without:airline',
            'airline' => 'required_without:route',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

        $startDate= "";
        $endDate = ""; 
        if($startDate = !$request->startDate || $endDate = !$request->endDate){
            $now = Carbon::now();
            $startDate = $now->startOfWeek()->format('Y-m-d H:i');
            $endDate = $now->endOfWeek()->format('Y-m-d H:i');
        }
        $schedules="";
        if($request->route){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_arrival_date between '$startDate' and '$endDate' and status = '6' and route_id = '$request->route_id' ");
            
        }else if($request->airline){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_arrival_date between '$startDate' and '$endDate' and status = '6' and airlineCode = '$request->airline' ");
        }
        if($schedules && count($schedules) > 0){
            return response()->json(['status' => true, 'message' => 'successful', 'data'=> $schedules ], 200);
        }
        return response()->json([ 'status' => false,'message' => 'No records found'], 200);
    }

    public function arrivalPerformanceByDateAndTime(Request $request){
        $validator = \Validator::make($request->all(), [
            'route' => 'required_without:airline',
            'airline' => 'required_without:route',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

        $startDate= "";
        $endDate = ""; 
        $startTime= "";
        $endTime = ""; 
        if($startDate = !$request->startDate || $endDate = !$request->endDate){
            $now = Carbon::now();
            $startDate = $now->startOfWeek()->format('Y-m-d H:i');
            $endDate = $now->endOfWeek()->format('Y-m-d H:i');
            $startTime = "00:00";
            $endTime = "23:59";
        }
        $schedules="";
        if($request->route){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_arrival_date between '$startDate' and '$endDate' and scheduled_arrival_time between '$startTime' and '$endTime' and status = '4' and route_id = '$request->route_id'"); 
        }else if($request->airline){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_arrival_date between '$startDate' and '$endDate' and scheduled_arrival_time between '$startTime' and '$endTime' and status = '4' and airlineCode = '$request->airline' ");
        }
        if($schedules && count($schedules) > 0){
            return response()->json(['status' => true, 'message' => 'successful', 'data'=> $schedules ], 200);
        }
        return response()->json([ 'status' => false,'message' => 'No records found'], 200);
    }

    public function cancelledFlightByDate(Request $request){
        $validator = \Validator::make($request->all(), [
            'route' => 'required_without:airline',
            'airline' => 'required_without:route',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

        $startDate= "";
        $endDate = ""; 
        if($startDate = !$request->startDate || $endDate = !$request->endDate){
            $now = Carbon::now();
            $startDate = $now->startOfWeek()->format('Y-m-d H:i');
            $endDate = $now->endOfWeek()->format('Y-m-d H:i');
        }
        $schedules="";
        if($request->route){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and status = '3' and route_id = '$request->route_id' ");
            
        }else if($request->airline){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and status = '3' and airlineCode = '$request->airline' ");
        }
        if($schedules && count($schedules) > 0){
            return response()->json(['status' => true, 'message' => 'successful', 'data'=> $schedules ], 200);
        }
        return response()->json([ 'status' => false,'message' => 'No records found'], 200);
    }

    public function cancelledFlightByDateAndTime(Request $request){
        $validator = \Validator::make($request->all(), [
            'route' => 'required_without:airline',
            'airline' => 'required_without:route',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

        $startDate= "";
        $endDate = ""; 
        $startTime= "";
        $endTime = ""; 
        if($startDate = !$request->startDate || $endDate = !$request->endDate){
            $now = Carbon::now();
            $startDate = $now->startOfWeek()->format('Y-m-d H:i');
            $endDate = $now->endOfWeek()->format('Y-m-d H:i');
            $startTime = "00:00";
            $endTime = "23:59";
        }
        $schedules="";
        if($request->route){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and scheduled_departure_time between '$startTime' and '$endTime' and status = '3' and route_id = '$request->route_id'"); 
        }else if($request->airline){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and scheduled_departure_time between '$startTime' and '$endTime' and status = '3' and airlineCode = '$request->airline' ");
        }
        if($schedules && count($schedules) > 0){
            return response()->json(['status' => true, 'message' => 'successful', 'data'=> $schedules ], 200);
        }
        return response()->json([ 'status' => false,'message' => 'No records found'], 200);
    }

    public function delayedFlightByDate(Request $request){
        $validator = \Validator::make($request->all(), [
            'route' => 'required_without:airline',
            'airline' => 'required_without:route',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

        $startDate= "";
        $endDate = ""; 
        if($startDate = !$request->startDate || $endDate = !$request->endDate){
            $now = Carbon::now();
            $startDate = $now->startOfWeek()->format('Y-m-d H:i');
            $endDate = $now->endOfWeek()->format('Y-m-d H:i');
        }
        $schedules="";
        if($request->route){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and status = '2' and route_id = '$request->route_id' ");
            
        }else if($request->airline){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and status = '2' and airlineCode = '$request->airline' ");
        }
       if($schedules && count($schedules) > 0){
            return response()->json(['status' => true, 'message' => 'successful', 'data'=> $schedules ], 200);
        }
        return response()->json([ 'status' => false,'message' => 'No records found'], 200);
    }

    public function delayedFlightByDateAndTime(Request $request){
        $validator = \Validator::make($request->all(), [
            'route' => 'required_without:airline',
            'airline' => 'required_without:route',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

        $startDate= "";
        $endDate = ""; 
        $startTime= "";
        $endTime = ""; 
        if($startDate = !$request->startDate || $endDate = !$request->endDate){
            $now = Carbon::now();
            $startDate = $now->startOfWeek()->format('Y-m-d H:i');
            $endDate = $now->endOfWeek()->format('Y-m-d H:i');
            $startTime = "00:00";
            $endTime = "23:59";
        }
        $schedules="";
        if($request->route){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and scheduled_departure_time between '$startTime' and '$endTime' and status = '2' and route_id = '$request->route_id'"); 
        }else if($request->airline){
            $schedules = DB::select("select al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$startDate' and '$endDate' and scheduled_departure_time between '$startTime' and '$endTime' and status = '2' and airlineCode = '$request->airline' ");
        }
        if($schedules && count($schedules) > 0){
            return response()->json(['status' => true, 'message' => 'successful', 'data'=> $schedules ], 200);
        }
        return response()->json([ 'status' => false,'message' => 'No records found'], 200);
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
        //return response()->json(['message' => $request->actual_arrival_time,'status' => true ], 200);
        $type = "";
        try{
            $schedule = Schedule::where('id', $id)->first();
        if(!$schedule){
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

        $input_count = count($request->all());
        while($input_count > 1){

            if(array_key_exists('actual_departure_time', $msg_array)) {
                $type = "actual_departure_time";
                $schedule->actual_departure_time =  $request->actual_departure_time;
            }
            if(array_key_exists('actual_departure_date', $msg_array)) {
                $type = "actual_departure_date";
                $schedule->actual_departure_date =  $request->actual_arrival_date;
            }
            if(array_key_exists('actual_arrival_date', $msg_array)) {
                $type = "actual_arrival_date";
                $schedule->actual_arrival_date =  $request->actual_arrival_date;
            }
            if(array_key_exists('actual_arrival_time', $msg_array)) {
                $type = "actual_arrival_time";
                $schedule->actual_arrival_time =  $request->actual_arrival_time;
            }
            if(array_key_exists('status', $msg_array)) {
                $type = "status";
                $schedule->status =  $request->status;
            }
            
            $input_count--;
        }

        if($schedule->save()){
            event(new ScheduleChanged($schedule, $type));
            return response()->json(['message' => 'Successful','status' => true ], 200);
        }
        return response()->json(['message' => 'Failed','status' => false ], 200);
    
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false
            ], 200);
        }
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
