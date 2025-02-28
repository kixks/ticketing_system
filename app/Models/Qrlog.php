<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Qrlog extends Model
{
    use HasApiTokens;
    protected $table = 'qrlogs';

    protected $fillable = [
        'ticket_id',
        'security_officer_name',
        'scanned_at',
        'status',
    ];
}
