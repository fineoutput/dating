<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;
    protected $table = 'interests';
    protected $fillable = [
        'name', 
        'desc', 
        'status', 
        'icon'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'interest', 'id');
    }
    
}
