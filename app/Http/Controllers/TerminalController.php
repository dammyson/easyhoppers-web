<?php

namespace App\Http\Controllers;

use App\Terminal;
use App\State;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TerminalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terminals = DB::select("select t.id, t.code, s.name from terminals t left join states s on t.state_id = s.id;");
        return view('terminal.index',[ 'terminals'=> $terminals]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::select('id','name')->get();
        return view('terminal.create',[ 'states'=> $states ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'code'      => 'required',
            'state'      => 'required',
        ]);

        $error = $validator->errors()->first();
        if($validator->fails()){
            Session::flash('error', $error);
            return back();
        }else{
            $terminal = new Terminal();
            $terminal->code = $request->code;
            $terminal->description = $request->code;
            $terminal->state_id = $request->state;
            if($terminal->save()){
                Session::flash('success', 'Your Data has successfully added');
                $terminals = DB::select("select t.id, t.code, s.name  from terminals t left join states s on t.state_id = s.id;");
                return view('terminal.index',[ 'terminals'=> $terminals ]);
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Terminal  $terminal
     * @return \Illuminate\Http\Response
     */
    public function show(Terminal $terminal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Terminal  $terminal
     * @return \Illuminate\Http\Response
     */
    public function edit(Terminal $terminal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Terminal  $terminal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Terminal $terminal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Terminal  $terminal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Terminal $terminal)
    {
        //
    }

    public function load_terminals($state_id){
       
        $terminals = Terminal::where('state_id', $state_id)->select('id','code')->get();
        if($terminals){
            if(count($terminals)>0){
                return response()->json(['message' => 'Successful','status' => true, 'terminals' => $terminals], 200);
            }
        }
        return response()->json(['message' => 'No terminal found','status' => false ], 200);
    }
}
