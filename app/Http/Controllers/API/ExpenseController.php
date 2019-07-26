<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Expense;
use App\ExpenseDetail;
use App\User;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
     //
    public function create_expense(Request $request){

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'budget' => 'required',
            'currency' => 'required',
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }
        try{
            $authUser = auth()->user();
            $authEmail = $authUser->email;
            $userCheck = Expense::where('user_email',$authEmail)->where('status',0)->first();
            if($userCheck){
                return response()->json(['message' => 'Please close current expense','status' => false ], 200);
            }

            $expense = new Expense();
            $expense->name = $request->name;
            $expense->budget = $request->budget;
            $expense->amount_spent = 0;
            $expense->user_email = $authEmail;
            $expense->currency = $request->currency;
            $expense->start_date = Carbon::now();

            if( $expense->save()){
                return response()->json(['message' => 'Successful','status' => true, 'expense_id' => $expense->id  ], 200);
            }else{
                return response()->json(['message' => 'Could not save records','status' => false ], 200);
            }
        }catch(\Exception $ex){
            return response()->json(['message' => $ex->getMessage(),'status' => false ], 200);
        }
    }

    public function all_expense(){
        $authUser = auth()->user();

        $expense = Expense::where('user_email',$authUser->email)->get();
        if($expense){
            if(count($expense)>0){
                return response()->json(['message' => 'Successful','status' => true, 'data' => $expense], 200);
            }
        }
        return response()->json(['message' => 'No expense found','status' => false ], 200);
    }

    public function expense_details($id){
        //$sum = 0 ;
        $items = array();
        $budgetArray = array();
        //$expense = ExpenseDetail::where('expense_id',$id)->get();
        $expense = DB::select("select a.id, expense_id, category, amount, description, a.created_at, a.updated_at, b.currency FROM eazyhopper_db.expense_details a  left join eazyhopper_db.expenses b on a.expense_id = b.id where expense_id = '$id';");
        if($expense){
            if(count($expense)>0){
                $o_expense = Expense::find($id);
                $budget = $o_expense->budget;
                foreach ($expense as $key => $value) {
                    $pieData = new ExpenseDetail();
                    $pieData->name = $value->category ." (".$expense[0]->currency." ".$value->amount .")";
                    $pieData->amount = (int)$value->amount;
                    //$sum += $value->amount;
                    array_push($items,$pieData);
                }
                $budget_details = new ExpenseDetail();
                $budget_details->budget = (int)$budget;
                $budget_details->amount_spent =  (int)$o_expense->amount_spent;
                $budget_details->percentage = ($o_expense->amount_spent/$budget)*100;
                array_push($budgetArray, $budget_details);
                return response()->json(['message' => 'Successful','status' => true, 'data' => $expense, 'pie_data'=> $items, 'budget_data' => $budgetArray], 200);
            }
            return response()->json(['message' => 'No expense found','status' => false], 200);
        }
        return response()->json(['message' => 'No expense found','status' => false ], 200);
    }

    public function add_expense_details(Request $request){

        $validator = \Validator::make($request->all(), [
            'category' => 'required',
            'amount' => 'required',
            'description' => 'required',
            'expense_id' => 'required',
            'date_of_expense' => 'required'
        ]);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(['message' => $error, 'status' => false ], 200);
        }
        try{

            $expCheck = Expense::where('id',$request->expense_id)->first();
            if(!$expCheck){
                return response()->json(['message' => 'No expense found','status' => false ], 200);
            }
            if($expCheck->status != '0'){
                return response()->json(['message' => 'Expense has been closed','status' => false ], 200);
            }

            $expense_details = new ExpenseDetail();
            $expense_details->category = $request->category;
            $expense_details->amount = $request->amount;
            $expense_details->description = $request->description;
            $expense_details->expense_id = $request->expense_id;
            $expense_details->date_of_expense = $request->date_of_expense;

            if( $expense_details->save()){
                $expCheck->amount_spent += $request->amount;
                $expCheck->save();
                return response()->json(['message' => 'Successful','status' => true ], 200);
            }else{
                return response()->json(['message' => 'Could not save records','status' => false ], 200);
            }
        }catch(\Exception $ex){
            return response()->json(['message' => $ex->getMessage(),'status' => false ], 200);
        }
    }

    public function close_expense($id){
        $expense = Expense::where('id',$id)->first();
        if($expense){
            $expense->status = '1';
            $expense->end_date = Carbon::now();
            if($expense->save()){
                return response()->json(['message' => 'Successful','status' => true], 200);
            }
            return response()->json(['message' => 'Could not save','status' => false], 200);
        }
        return response()->json(['message' => 'No expense found','status' => false ], 200);
    }
}
