<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vibes extends Model
{
    use HasFactory;
    protected $table = 'vibes';
    protected $fillable = [
        'name', 
        'activity_id',
        'image', 
        'status', 
        'icon'
    ];
    
}
