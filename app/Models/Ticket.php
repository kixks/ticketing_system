<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Ticket extends Model
{
    use HasApiTokens;

    protected $table = 'tickets';

    protected $fillable = [
        'user_id',
        'plate_number',
        'car_type',
        'trip_details',
        'passenger_count',
        'departure_time',
        'expected_return_time',
        'status',
        'qr_code'
    ];

}
