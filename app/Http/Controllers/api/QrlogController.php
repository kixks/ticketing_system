<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QrlogResource;
use App\Models\Qrlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class QrlogController extends Controller
{
    public function store(Request $request){
        // Validate the request
        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required|exists:tickets,id', 
            'security_officer_name'=>'required|string|max:255'
        ]);


        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()], 422);
        }
    
    
        $qrlog = Qrlog::create([
            'ticket_id' => $request->ticket_id,
            'security_officer_name' => $request->security_officer_name,
            'scanned_at' => now(), // Store current timestamp
            'status' => 'Departed'
        ]);


        return response()->json(['message'=>'Qr Has Been Scanned Successfully',
                                'QRlogs' => new QrlogResource($qrlog )],200);

    }
}
