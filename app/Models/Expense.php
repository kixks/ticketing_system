<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Expense extends Model
{
    use HasApiTokens;
    protected $table = 'expenses';

    protected $fillable = [
        'ticket_id',
        'expense_type',
        'amount',
        'remarks'
    ];
}
