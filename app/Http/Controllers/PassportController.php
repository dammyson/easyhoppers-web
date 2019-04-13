<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',

        ]);
            
        $error = $validator->errors()->first();
        if ($validator->fails()) {
         return response()->json([
             'message' => $error,
             'status' => false
         ], 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
 
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
            $token = auth()->user()->createToken($request->email)->accessToken;
            return response()->json(['status' => true, 'message'=> 'Login successful','token' => $token, ], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'UnAuthorised',''], 401);
        }
    }
 
    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
}
