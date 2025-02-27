<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Guard extends Model
{
    use HasFactory;
    protected $table = 'security_officer';

    protected $fillable = [
        'name',
    ];
}
