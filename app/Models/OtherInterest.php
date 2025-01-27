<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherInterest extends Model
{
    use HasFactory;
    protected $table = 'other_interest';
    protected $fillable = [
        'user_id', 
        'activity_id', 
        'confirm',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'interest', 'id');
    }
    
}
