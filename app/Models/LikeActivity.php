<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeActivity extends Model
{
    use HasFactory;
    protected $table = 'like_activity';
    protected $fillable = [
        'user_id', 
        'activity_id', 
        'status', 
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'interest', 'id');
    }
    
}
