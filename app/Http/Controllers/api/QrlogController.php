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
            'qr_code' => 'required|exists:tickets,qr_code',
            'security_officer_name'=>'required|string|max:50'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ticket_id = $this->checkQR($request->qr_code);

        $now = Carbon::now('Asia/Manila'); 

        $ticket_expiration = $this->checkTicketExpiration($ticket_id);
    
        if (Carbon::now()->greaterThan($ticket_expiration)) {
            return response()->json([
                'message' => 'This ticket has expired. Please contact the admin for assistance.'
            ], 403); 
        }
    
        $qrlog = Qrlog::create([
            'ticket_id' => $ticket_id,
            'security_officer_name' => $request->security_officer_name, 
            'scanned_at' => $now , // Store current date and time
            'status' => 'Departed'
        ]);
    
        return response()->json([
            'message' => 'QR has been scanned successfully.',
            'QRlogs' => new QrlogResource($qrlog)
        ], 200);
    }

    

    public function checkQR($qrstring)
    {

        $ticket_id = Ticket::where('qr_code',$qrstring)->first()->id;

        return $ticket_id;
    }

    public function checkTicketExpiration($ticket_id)
    {
        // Fetch the ticket details
        $ticket = Ticket::find($ticket_id);
    
        // Check if ticket is expired (with a 1-day allowance)
        $expiry_with_allowance = Carbon::parse($ticket->expected_return_time)->addDay(); // +1 day grace period

        return $expiry_with_allowance;
    }



    public function arrived(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|exists:tickets,qr_code',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ticket_id = $this->checkQR($request->qr_code);

        // Find the QR log where ticket_id matches and status is currently 'Departed'
        $qrlog = Qrlog::where('ticket_id', $ticket_id)
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
