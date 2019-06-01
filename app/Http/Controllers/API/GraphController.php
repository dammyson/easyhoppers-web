<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\ViewModel\ScheduleVM;

class GraphController extends Controller
{
    //
    public function graph(Request $request){
        $validator = \Validator::make($request->all(), [
            'airline_code' => 'required',
            'route_id' => 'required',
            'type' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

        $departure_array = array();
        $arrival_array = array();
       

        // if($request->type == 'day'){
            $startTime = $request->from;
            $endTime = $request->to;

            $early_departure_day = self::early_departure_day($startTime, $endTime, $request->airline_code, $request->route_id);
            $early_arrival_day = self::early_arrival_day($startTime, $endTime, $request->airline_code, $request->route_id);
            $onTime_departure_day = self::onTime_departure_day($startTime, $endTime, $request->airline_code, $request->route_id);
            $onTime_arrival_day = self::onTime_arrival_day($startTime, $endTime, $request->airline_code, $request->route_id);
            $delayed_departure_day = self::delayed_departure_day($startTime, $endTime, $request->airline_code, $request->route_id);
            $delayed_arrival_day = self::delayed_arrival_day($startTime, $endTime, $request->airline_code, $request->route_id);
            $cancelled_departure_day = self::cancelled_departure_day($startTime, $endTime, $request->airline_code, $request->route_id);
            $resheduled_departure_day = self::resheduled_departure_day($startTime, $endTime, $request->airline_code, $request->route_id);
            
            array_push($departure_array,$early_departure_day);
            array_push($departure_array,$onTime_departure_day);
            array_push($departure_array,$delayed_departure_day);
            array_push($departure_array,$cancelled_departure_day);

            array_push($arrival_array,$early_arrival_day);
            array_push($arrival_array,$onTime_arrival_day);
            array_push($arrival_array,$delayed_arrival_day);

            
            $items =  new \stdClass;
            $items->departure = $departure_array;
            $items->arrival = $arrival_array;

            return response()->json([ 'status' => true,'message' => 'Successful', 'data' => $items], 200);
        // }else{
            //$schedules = Schedule::whereBetween('scheduled_departure_date',[$weekStartDate, $weekEndDate])->get();

        //     return response()->json([ 'status' => false,'message' => 'Feature not yet available'], 200);
        // }
        return response()->json([ 'status' => false,'message' => 'Something went wrong'], 200);
    }

    public function early_departure_day($startDate, $endDate, $airlineCode, $route_id){
        $result = DB::select("select '0' as x, '0' as y union all select DATE_FORMAT(scheduled_departure_time, '%H:%i')  as x, ifnull(DATE_FORMAT(actual_departure_time, '%H:%i'),0) as y from schedules where scheduled_departure_date between '$startDate' and '$endDate'  and route_id =  '$route_id' and airlineCode = '$airlineCode' and status = '12';");
        return $result;
    }

    public function early_arrival_day($startDate, $endDate, $airlineCode, $route_id){

        $result = DB::select("select '0' as x, '0' as y union all select DATE_FORMAT(scheduled_departure_time, '%H:%i')  as x, ifnull(DATE_FORMAT(actual_departure_time, '%H:%i'),0) as y from schedules where scheduled_departure_date between '$startDate' and '$endDate'  and route_id =  '$route_id' and airlineCode = '$airlineCode' and status = '11';");
        return $result;
    }

    public function onTime_departure_day($startDate, $endDate, $airlineCode, $route_id){

        $result = DB::select("select '0' as x, '0' as y union all select DATE_FORMAT(scheduled_departure_time, '%H:%i')  as x, ifnull(DATE_FORMAT(actual_departure_time, '%H:%i'),0) as y from schedules where scheduled_departure_date between '$startDate' and '$endDate'  and route_id =  '$route_id' and airlineCode = '$airlineCode' and status = '2';");
        return $result;
    }

    public function onTime_arrival_day($startDate, $endDate, $airlineCode, $route_id){

        $result = DB::select("select '0' as x, '0' as y union all select DATE_FORMAT(scheduled_departure_time, '%H:%i')  as x, ifnull(DATE_FORMAT(actual_departure_time, '%H:%i'),0) as y from schedules where scheduled_departure_date between '$startDate' and '$endDate'  and route_id =  '$route_id' and airlineCode = '$airlineCode' and status = '1';");
        return $result;
    }

    public function delayed_departure_day($startDate, $endDate, $airlineCode, $route_id){

        $result = DB::select("select '0' as x, '0' as y union all select DATE_FORMAT(scheduled_departure_time, '%H:%i')  as x, ifnull(DATE_FORMAT(actual_departure_time, '%H:%i'),0) as y from schedules where scheduled_departure_date between '$startDate' and '$endDate'  and route_id =  '$route_id' and airlineCode = '$airlineCode' and status = '4';");
        return $result;
    }

    public function delayed_arrival_day($startDate, $endDate, $airlineCode, $route_id){

        $result = DB::select("select '0' as x, '0' as y union all select DATE_FORMAT(scheduled_departure_time, '%H:%i')  as x, ifnull(DATE_FORMAT(actual_departure_time, '%H:%i'),0) as y from schedules where scheduled_departure_date between '$startDate' and '$endDate'  and route_id =  '$route_id' and airlineCode = '$airlineCode' and status = '3';");
        return $result;
    }

    public function cancelled_departure_day($startDate, $endDate, $airlineCode, $route_id){

        $result = DB::select("select '0' as x, '0' as y union all select DATE_FORMAT(scheduled_departure_time, '%H:%i')  as x, ifnull(DATE_FORMAT(actual_departure_time, '%H:%i'),0) as y from schedules where scheduled_departure_date between '$startDate' and '$endDate'  and route_id =  '$route_id' and airlineCode = '$airlineCode' and status = '5';");
        return $result;
    }

    public function resheduled_departure_day($startDate, $endDate, $airlineCode, $route_id){

        $result = DB::select("select '0' as x, '0' as y union all select DATE_FORMAT(scheduled_departure_time, '%H:%i')  as x, ifnull(DATE_FORMAT(actual_departure_time, '%H:%i'),0) as y from schedules where scheduled_departure_date between '$startDate' and '$endDate'  and route_id =  '$route_id' and airlineCode = '$airlineCode' and status = '6';");
        return $result;
    }
}
