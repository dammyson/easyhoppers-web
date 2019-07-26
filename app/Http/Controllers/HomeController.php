<?php

namespace App\Http\Controllers;
use App\User;
use App\Role;
use App\State;
use Session;
use Excel;
use File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $now = Carbon::now();
        $activeUsers=0;
        $inactiveUsers=0;
        $activeAgents=0;
        $inactiveAgents=0;
        $users = User::whereHas('roles', function ($query) { $query->where('name', '=', 'customer');})->get();
        $agents = User::whereHas('roles', function ($query) { $query->where('name', '=', 'agent');})->get();
        $totalUsers = $users->count();
        $totalAgents = $agents->count();
        foreach($users as $user){
            if(( $user->updated_at->diff($now)->h <5) ){
                $activeUsers++;
            }else{
                $inactiveUsers++;
            }
        }

        foreach($agents as $agent){
            if(( $agent->updated_at->diff($now)->h <5) ){
                $activeAgents++;
            }else{
                $inactiveAgents++;
            }
        }

        return view('home',['totalUsers'=>$totalUsers,'totalAgents'=>$totalAgents,'activeUsers'=>$activeUsers,'activeAgents'=>$activeAgents,'inactiveUsers'=>$inactiveAgents,'activeUsers'=>$activeUsers, 'inactiveUsers'=>$inactiveUsers]);
    }

    public function test(Request $request){
        $request->user()->authorizeRoles(['supervisor', 'superadmin']);
        return view('home');
    }

    public function profile(){
        return view('user.profile');
    }

    public function list(){
       
        $filter = Input::get('filter', false);
        $now = Carbon::now();
        $activeUsers=0;
        $inactiveUsers=0;
        $activeAgents=0;
        $inactiveAgents=0;
        $users = User::whereHas('roles', function ($query) { $query->where('name', '=', 'customer');})->get();
        $agents = User::whereHas('roles', function ($query) { $query->where('name', '=', 'agent');})->get();
        $totalUsers = $users->count();
        $totalAgents = $agents->count();
        foreach($users as $user){
            if(( $user->updated_at->diff($now)->h <5) ){
                $activeUsers++;
            }else{
                $inactiveUsers++;
            }
        }

        foreach($agents as $agent){
            if(( $agent->updated_at->diff($now)->h <5) ){
                $activeAgents++;
            }else{
                $inactiveAgents++;
            }
        }
        if($filter){
            if($filter == 'Agents'){
                $users = DB::select('select a.id, a.name, a.state, a.city, a.unique_id, a.status, a.email, a.created_at, a.updated_at, c.name as role from users a left join role_user b on a.id = b.user_id left join roles c on b.role_id = c.id where c.name = \'agent\';');
            }else{
                $users = DB::select('select a.id, a.name, a.state, a.city, a.unique_id, a.status, a.email, a.created_at, a.updated_at, c.name as role from users a left join role_user b on a.id = b.user_id left join roles c on b.role_id = c.id where c.name = \'customer\';');
            }
        }else{
            $users = DB::select('select a.id, a.name, a.state, a.city, a.unique_id, a.status, a.email, a.created_at, a.updated_at, c.name as role from users a left join role_user b on a.id = b.user_id left join roles c on b.role_id = c.id;');
        }
        
        return view('user.list',['users'=>$users, 'totalUsers'=>$totalUsers,'totalAgents'=>$totalAgents,'activeUsers'=>$activeUsers,'activeAgents'=>$activeAgents,'inactiveUsers'=>$inactiveAgents,'activeUsers'=>$activeUsers, 'inactiveUsers'=>$inactiveUsers]);
    }

    public function create(){
        $unique_id = rand(pow(10, 9), pow(10, 10)-1);
        $states = State::select('id','name')->get();
        return view('user.create',['unique_id'=>$unique_id, 'states'=> $states ]);
    }

    public function store(Request $request){
        $custom_errors = array();
        $digits = 10;
        $validator = Validator::make($request->all(),[
            'file'      => 'required|max:2048',
        ]);

        $error = $validator->errors()->first();
        if($validator->fails()){
            Session::flash('error', $error);
            return back();
        }
        else 
        {
            try {
                $role_agent  = Role::where('name', 'agent')->first();
                if($request->hasFile('file')){
                    $extension = File::extension($request->file->getClientOriginalName());
                    if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
         
                        $path = $request->file->getRealPath();
                        $data = Excel::load($path, function($reader) {
                        })->get();
                        if(!empty($data) && $data->count()){
                            #var_dump($data);
                            foreach ($data as $key => $value) {
                                
                                $unique_id = rand(pow(10, $digits-1), pow(10, $digits)-1);
                              // $isValid =  self::validate_data($value);
                                $isValid = true;
                                if($isValid){
                                    $insert[] = [
                                        'name' => $value->fullname,
                                        'email' => $value->email,
                                        'phone' => $value->phone,
                                        'unique_id' => $unique_id,
                                        'state' => $value->state,
                                        'city' => $value->city,
                                        'terminal' => $value->terminal,
                                        'password' => $value->password,
                                        'status' => 0,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    ];
                                }else{
                                    $custom_errors[$value['1']] = '';
                                }
                                $userCheck = User::where('email', $value->email)->first();
                                if($userCheck){
                                    Session::flash('error', 'User already exists: '.$value->email);
                                    return back();
                                }

                            }
         
                            if(!empty($insert)){ 
                                
                                
                                $agent = DB::table('users')->insert($insert);
                               
                                if ($agent) {
                                    //dd($insert);
                                    foreach($insert as $insUser){
                                        //dd($insUser);
                                        $user = User::where('email', $insUser['email'])->first();
                                        $user->roles()->attach($role_agent);
                                    }
                                   
                                    Session::flash('success', 'Your Data has successfully imported');
                                    $users = DB::select('select a.id, a.name, a.unique_id, a.status, a.email, a.created_at, a.updated_at, c.name as role from users a left join role_user b on a.id = b.user_id left join roles c on b.role_id = c.id;');
                                    return view('user.list',['users'=>$users]);
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
                return view('user.create');
          }
        }
    }

    public function save(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'unique_id' => 'required|min:3',
            'phone' => 'required|min:3',
            'email' => 'required|email',
            'state' => 'required',
            'city' => 'required',
            'terminal' => 'required'
        ]);

        $error = $validator->errors()->first();
        if($validator->fails()){
            Session::flash('error', $error);
            return back();
        }
        $role_agent  = Role::where('name', 'agent')->first();

        $user = new User();
        $user->name = $request->name;
        $user->unique_id = $request->unique_id;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->terminal = $request->terminal;
        $user->status = 0;
        $user->password = bcrypt($request->password);
        $user->created_at = Carbon::now();
        $user->updated_at = Carbon::now();

        if($user->save()){
            $user->roles()->attach($role_agent);
            Session::flash('success', 'Agent added successfully !!!');
            return back();
        }else{
            Session::flash('error', 'Could not add agent !!!');
            return back();
        }
    }

    public function user_details($id){
        $user = DB::select('select a.id, a.name, a.state, a.city, a.unique_id, a.status, a.email, a.created_at, a.updated_at, c.name as role from users a left join role_user b on a.id = b.user_id left join roles c on b.role_id = c.id where a.id = '.$id.';');
        $user = $user[0];
        return view('user.details',['user'=>$user]);
    }

    public function change_agent_status($user_id, $status){
        $user = User::find($user_id);
        $user->status = $status;
        if($user->save()){
            Session::flash('success', 'Action completed !!!');
            return redirect()->route('userListing');
        }else{
            Session::flash('error', 'Could not complete action !!!');
            return redirect()->route('userListing');
        }
    }

    public function delete_agent($id){
        $user = User::find($id);
       $user->delete();
        
        if(!User::find($id)){
            return response()->json(['message' => 'User Deleted','status' => true ], 200);
        }else{
            return response()->json(['message' => 'Could not delete user','status' => false ], 200);
        }

    }

    public function update_user(Request $request){

        $authUser = auth()->user();
        $id = $authUser->id;
        $user = User::find($id);

        if(!$user){
            return response()->json(['message' => 'No User found', 'status' => false ], 200);
        }

        if(!$msg_array = json_decode($request->getContent(), true)){
            return response()->json(['message' => 'Message body is empty', 'status' => false, 'as'=>$msg_array ], 200);
        };

        $input_count = count($request->all());
        while($input_count > 0){
            if(array_key_exists('firstname', $msg_array)) {
                $user->firstname =  $request->firstname;
            }
            if(array_key_exists('lastname', $msg_array)) {
                $user->lastname =  $request->lastname;
            }
            if(array_key_exists('DOB', $msg_array)) {
                $user->DOB =  $request->DOB;
            }
            if(array_key_exists('phone', $msg_array)) {
                $user->phone =  $request->phone;
            }
            if(array_key_exists('state', $msg_array)) {
                $user->state =  $request->state;
            }
            if(array_key_exists('terminal', $msg_array)) {
                $user->terminal =  $request->terminal;
            }
            if(array_key_exists('unique_id', $msg_array)) {
                $user->unique_id =  $request->unique_id;
            }
            if(array_key_exists('gender', $msg_array)) {
                $user->gender =  $request->gender;
            }
            if(array_key_exists('occupation', $msg_array)) {
                $user->occupation =  $request->occupation;
            }
            $input_count--;
        }

        if($user->save()){
            return response()->json(['message' => 'Successful','status' => true ], 200);
        }
        return response()->json(['message' => 'Failed','status' => false ], 200);
    }
}
