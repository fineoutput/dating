<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnverifyUser extends Model
{
    use HasFactory;

    // Specify the table name if it does not follow Laravel's naming convention
    protected $table = 'unverify_user';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'number', 
        'number_verify', 
        'name', 
        'email', 
        'email_verify', 
        'age', 
        'gender', 
        'looking_for', 
        'interest', 
        'profile_image',
        'state', 
        'city', 
        'status', 
        'password'
    ];

    // Optionally, you can define hidden fields like password for security
    protected $hidden = [
        'password', // Hide the password from array and JSON outputs
        'id', // Hide the password from array and JSON outputs
    ];

    // Optionally, you can define casts for certain fields (e.g., dates or JSON fields)
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

