<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasFactory;
    protected $table = 'user_otp';
    protected $fillable = [
        'source_name',
        'otp',
        'expires_at',
        'type',
    ];

    protected $casts = [
        'expires_at' => 'datetime',  
    ];
    protected $hidden = [
        'otp', 
    ];
}
