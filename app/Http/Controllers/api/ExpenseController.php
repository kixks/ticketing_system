<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function index(){
        $ticket = Expense::get();

        if (!$ticket) {
            return response()->json(['message' => 'No record Available'],404);
            return ExpenseResource::collection($ticket);
        } 
        
        return ExpenseResource::collection($ticket);

        
    }
    public function store(Request $request){
        // Validate the request
        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required|exists:tickets,id', // Must exist in the tickets table
            'expense_type' => 'required|in:gas,food,hotel,allowance', // Only valid ENUM values
            'amount' => 'required|numeric|min:0', // Amount should be a valid number
            'remarks' => 'nullable|string|max:1000', // Remarks are optional
        ]);


        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()], 422);
        }
    
    
        $expense  = Expense::create($validator->validated());

        return response()->json(['message'=>'Expense Has Been Added Successfully',
                                'Expense' => new ExpenseResource($expense )],200);

    }

    public function show($id)
    {
        $expense = Expense::find($id);
    
        if (!$expense) {
            return response()->json(['message' => 'Expense Not Found'], 404);
        }
    
        return new ExpenseResource($expense);
    }

    public function update(Request $request, Expense $expense){

        $validator = Validator::make($request->all(), [
            'expense_type' => 'required|in:gas,food,hotel,allowance', // Only valid ENUM values
            'amount' => 'required|numeric|min:0', // Amount should be a valid number
            'remarks' => 'nullable|string|max:1000', // Remarks are optional
        ]);


        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()], 422);
        }
    
    
        $expense->update($validator->validated());

        return response()->json(['message'=>'Expense Updated Successfully',
                                'Expense' => new ExpenseResource($expense )],200);
    }

    public function destroy($id){
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json(['message' => 'Expense Not Found'], 404);
        }
        $expense->delete();
    
        return response()->json(['message' => 'Data Deleted Successfully'], 200);
    }

}
