<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinCategory extends Model
{
    protected $table = 'coincategory';
    protected $fillable = [
        'category',
        'extend_chat_coin',
        'monthly_activities_coin',
        'monthly_interests_coin',
        'interest_messages_coin',
        'cost',
        'description',
        'status',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}

