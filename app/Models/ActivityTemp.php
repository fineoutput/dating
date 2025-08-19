<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTemp extends Model
{
    use HasFactory;
    protected $table = 'activity_temp_table';
    protected $fillable = [
        'user_id',  
        'where_to',  
        'when_time', 
        'how_many',
        'start_time',
        'end_time',
        'interests_id',
        'vibe_id',
        'expense_id',
        'other_activity',
        'status',
        'title',
        'description',
        'location',
        'amount',
        'rendom',
        'friend_rendom',
        'friend_number',
        'image',
    ];


    public function vibe()
    {
        return $this->belongsTo(Vibes::class, 'vibe_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
