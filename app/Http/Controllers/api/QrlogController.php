<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QrlogResource;
use App\Models\Qrlog;
use App\Models\Ticket;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class QrlogController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required|exists:tickets,id',
            'security_officer_name'=>'required|string|max:50'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Fetch the ticket details
        $ticket = Ticket::find($request->ticket_id);

        $now = Carbon::now('Asia/Manila'); 
    
        // Check if ticket is expired (with a 1-day allowance)
        $expiry_with_allowance = Carbon::parse($ticket->expected_return_time)->addDay(); // +1 day grace period
    
        if (Carbon::now()->greaterThan($expiry_with_allowance)) {
            return response()->json([
                'message' => 'This ticket has expired. Please contact the admin for assistance.'
            ], 403); 
        }
    
        $qrlog = Qrlog::create([
            'ticket_id' => $request->ticket_id,
            'security_officer_name' => $request->security_officer_name, 
            'scanned_at' => $now , // Store current date and time
            'status' => 'Departed'
        ]);
    
        return response()->json([
            'message' => 'QR has been scanned successfully.',
            'QRlogs' => new QrlogResource($qrlog)
        ], 200);
    }

    public function arrived(Request $request){

        $request->validate([
            'ticket_id' => 'required|exists:qrlogs,ticket_id', //id must exist in the qrlogs table
        ]);

        // Find the QR log where ticket_id matches and status is currently 'Departed'
        $qrlog = Qrlog::where('ticket_id', $request->ticket_id)
                    ->where('status', 'Departed')
                    ->first();

        // Check if the QR log exists and has 'Departed' status
        if (!$qrlog) {
            return response()->json(['message' => 'QR log not found or already marked as Arrived.'], 404);
        }

        $qrlog->update(['status' => 'Arrived']);

        return response()->json(['message' => 'QR log status updated to Arrived.'], 200);
    }
 
}
