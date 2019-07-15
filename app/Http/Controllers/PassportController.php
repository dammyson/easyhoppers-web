<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use Illuminate\Http\Request;
use Session;
use Auth;
use Illuminate\Support\Facades\Validator;

class PassportController extends Controller
{
   /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $digits = 10;
        $request->unique_id = rand(pow(10, $digits-1), pow(10, $digits)-1);
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'state' => 'required|min:3',
            'city' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
            
        $error = $validator->errors()->first();
        if ($validator->fails()) {
         return response()->json([
             'message' => $error,
             'status' => false
         ], 200);
        }

        $role_customer  = Role::where('name', 'customer')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'state' => $request->state,
            'city' => $request->city,
            'unique_id' => $request->unique_id,
            'password' => bcrypt($request->password)
        ]);
        
        $user->roles()->attach($role_customer);
        $token = $user->createToken($request->email)->accessToken;
 
        return response()->json(
            [
                'status' => true,
                'message' => 'User created successfully',
                'token' => $token,
                'name' => $request->name,
                'email' => $request->email,
            ]
            , 200);
    }
 
    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'mobile_token' => 'required'
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
 
        if (auth()->attempt($credentials)) {
            $user = Auth::user();
            if($user->status != 0){
                if($user->status == 1){
                    return response()->json(['status' => false, 'message' => 'Account suspended'], 401);
                }else if($user->status == 2){
                    return response()->json(['status' => false, 'message' => 'Account deactivated'], 401);
                }
            }
            $user_role = $user->roles->first()->name;
            $token = auth()->user()->createToken($request->email)->accessToken;
            $user->updated_at = \Carbon\Carbon::now();
            $user->mobile_token = $request->mobile_token;
            $user->save();
            return response()->json(['status' => true, 'message'=> 'Login successful','token' => $token, 'Role' => $user_role ], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'UnAuthorised'], 401);
        }
    }
 
    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        $user = auth()->user();
        if($user){
            return response()->json(['status' => true,'message' => 'Successful','user' => $user], 200);
        }
        return response()->json(['status' => false,'message' => 'No User found'], 200);
    }

    public function users(){
        $users = User::all();
        if($users){
            return response()->json(['status' => true,'message'=> 'Successful','users' => $users], 200);
        }
        return response()->json(['status' => false,'message' => 'No user found'], 200);
    }

    public function getSchedules()
    {
        $authUser = auth()->user();
        $items = array();
        $isSubscribed = false;
        try{
            $now = \Carbon\Carbon::now();
            $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
            $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
            $schedules = \DB::select("select s.id, al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id where s.scheduled_departure_date between '$weekStartDate' and '$weekEndDate' ");
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
                        $iSchedule = new \stdClass;
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

    
}
