<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index(){
        $ticket = Ticket::get();

        if (!$ticket) {
            return response()->json(['message' => 'No record Available'],200);
            return TicketResource::collection($ticket);
        } 
        
        return TicketResource::collection($ticket);

        
    }
    public function store(Request $request){
            // Validate the request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // Must exist in the users table
            'plate_number' => 'required|string|max:20|regex:/^[A-Za-z0-9-]+$/', // Alphanumeric + hyphen
            'car_type' => 'required|string|max:50',
            'trip_details' => 'required|string|max:1000',
            'passenger_count' => 'required|integer|min:1',
            'departure_time' => 'required|date|after_or_equal:now', // Must be in the future
            'expected_return_time' => 'nullable|date|after:departure_time', // Must be after departure
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()], 422);
        }
        
        

        $ticket = Ticket::create([
            'user_id' => $request->user_id,
            'plate_number' => $request->plate_number,
            'car_type' => $request->car_type,
            'trip_details' => $request->trip_details,
            'passenger_count' => $request->passenger_count,
            'departure_time' => $request->departure_time,
            'expected_return_time' => $request->expected_return_time,
            'qr_code' => 'TICKET-' . Str::random(8), // Generates a unique ticket code
            'status' => 'pending'
        ]);

        return response()->json(['message'=>'Ticket Created Successfully',
                                'ticket' => new TicketResource($ticket)],200);

    }
}
