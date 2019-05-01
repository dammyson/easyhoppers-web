<?php

namespace App\Http\Controllers;
use App\User;
use App\Role;
use Session;
use Excel;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
        return view('home');
    }

    public function test(Request $request){
        $request->user()->authorizeRoles(['supervisor', 'superadmin']);
        return view('home');
    }

    public function profile(){
        return view('user.profile');
    }

    public function list(){
        $users = DB::select('select a.id, a.name, a.unique_id, a.status, a.email, a.created_at, a.updated_at, c.name as role from users a left join role_user b on a.id = b.user_id left join roles c on b.role_id = c.id;');
        return view('user.list',['users'=>$users]);
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
                                
                               $isValid =  self::validate_data($value);
                                if($isValid){
                                    $insert[] = [
                                        'fullname' => $value->airline_code,
                                        'email' => $request->schedule_name,
                                        'phone' => $value->amount,
                                        'password' => $value->route_id,
                                        'status' => 0,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    ];
                                }else{
                                    $custom_errors[$value['1']] = '';
                                }
                                
                            }
         
                            if(!empty($insert)){
         
                                $agent = DB::table('users')->insert($insert);
                               
                                if ($agent) {
                                    $user = User::where('email', $insert['email']);
                                    $user->roles()->attach($role_agent);
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
}
