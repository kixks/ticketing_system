<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qrlog extends Model
{
    protected $table = 'qrlogs';

    protected $fillable = [
        'ticket_id',
        'security_officer_name',
        'scanned_at',
        'status',
    ];
}
