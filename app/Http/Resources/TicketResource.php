<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'user_id' => $this->user_id,
            'plate_number' => $this->plate_number,
            'car_type' => $this->car_type,
            'trip_details' => $this->trip_details,
            'passenger_count' => $this->passenger_count,
            'departure_time' => $this->departure_time,
            'expected_return_time' => $this->expected_return_time,
            'status' => $this->status,
            'qr_code' => $this->qr_code
        ];
    }
}
