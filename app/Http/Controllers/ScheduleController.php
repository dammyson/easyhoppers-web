<?php

namespace App\Http\Controllers;

use App\Schedule;
use Session;
use Excel;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::all();
        return view('schedule.index',['schedules'=>$schedules]);
    }

    public function schedule_list(){
        $schedules = DB::select("select s.id, al.name, s.description, s.scheduled_departure_time, s.scheduled_arrival_time, r.departure_port, r.arrival_port, status, s.scheduled_departure_date, s.scheduled_arrival_date FROM schedules s  left join airlines al on al.code = s.airlineCode LEFT JOIN routes r on r.id = s.route_id order by s.scheduled_departure_date desc ");

        return view('schedule.listing',['schedules'=>$schedules]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('schedule.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        //$arrayName = array('' => , );
        $custom_errors = array();
        $validator = Validator::make($request->all(),[
            'file'      => 'required|max:2048',
            'schedule_name' => 'required'
        ]);

        $error = $validator->errors()->first();
        if($validator->fails()){
            Session::flash('error', $error);
            return back();
        }
        else 
        {
            try {
                if($request->hasFile('file')){
                    $extension = File::extension($request->file->getClientOriginalName());
                    if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
         
                        $path = $request->file->getRealPath();
                        $data = Excel::load($path, function($reader) {
                        })->get();
                        if(!empty($data) && $data->count()){
                            #var_dump($data);
                            foreach ($data as $key => $value) {
                                
                               $isValid =  self::validate_data($value);
                                if($isValid){
                                    $insert[] = [
                                        'airlineCode' => $value->airline_code,
                                        'schedule_name' => $request->schedule_name,
                                        'amount' => $value->amount,
                                        'route_id' => $value->route_id,
                                        'scheduled_departure_date' => $value->departure_date,
                                        'scheduled_arrival_date' => $value->arrival_date,
                                        'scheduled_departure_time' => $value->departure_time,
                                        'scheduled_arrival_time' => $value->arrival_time,
                                        'description' => $value->description,
                                        'status' => 0,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    ];
                                }else{
                                    $custom_errors[$value['1']] = '';
                                }
                                
                            }
         
                            if(!empty($insert)){
         
                                $insertData = DB::table('schedules')->insert($insert);
                                if ($insertData) {
                                    Session::flash('success', 'Your Data has successfully imported');
                                    $schedules = Schedule::all();
                                    return view('schedule.index',['schedules'=>$schedules]);
                                }else {                        
                                    Session::flash('error', 'Error inserting the data..');
                                    return back();
                                }
                            }
                        }
         
                        return back();
         
                    }else {
                        Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                        return back();
                    }
                }
            } catch (\Exception $e) {
                \Session::flash('error', $e->getMessage());
                return view('schedule.create');
            }
        } 

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        //
    }

    function validate_data($data){
        return true;
    }   
}
