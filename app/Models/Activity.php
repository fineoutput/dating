<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $table = 'activity_table';
    protected $fillable = [
        'user_id',  
        'where_to',  
        'when_time', 
        'how_many',
        'start_time',
        'end_time',
        'interests_id',
        'expense_id',
        'other_activity',
        'status',
    ];
    
}
