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

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
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
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
 
        if (auth()->attempt($credentials)) {
            $user = Auth::user();
            $user_role = $user->roles->first()->name;
            $token = auth()->user()->createToken($request->email)->accessToken;
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

    
}
