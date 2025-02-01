<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;
    protected $table = 'user_subscription';
    protected $fillable = [
        'user_id',  
        'amount',  
        'expire_days',  
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
