<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlideLike extends Model
{
    use HasFactory;
    protected $table = 'slide_like';
    protected $fillable = [
        'matching_user',
        'matched_user',
        'super_like',
        'liked_user',
        'dislike',
        'status',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    
}
