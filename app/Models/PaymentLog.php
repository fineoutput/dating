<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    use HasFactory;
    protected $table = 'payment_log';
   protected $fillable = [
        'order_id',
        'user_id',
        'amount',
        'status',
        'event_type',
        'raw_response',
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
