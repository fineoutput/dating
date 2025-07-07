<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminCity extends Model
{
    use HasFactory;
    protected $table = 'admin_city';
    protected $fillable = [  
        'city_name',  
        'status',
    ];


}
