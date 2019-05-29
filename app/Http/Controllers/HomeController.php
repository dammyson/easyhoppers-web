<?php

namespace App\Http\Controllers;
use App\User;
use App\Role;
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
        return view('user.create');
    }

    public function store(Request $request){
        $custom_errors = array();
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
                                
                              // $isValid =  self::validate_data($value);
                                $isValid = true;
                                if($isValid){
                                    $insert[] = [
                                        'name' => $value->fullname,
                                        'email' => $value->email,
                                        'unique_id' => $value->phone,
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
}
