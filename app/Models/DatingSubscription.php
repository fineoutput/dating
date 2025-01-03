<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatingSubscription extends Model
{
    protected $table = 'dating_subscription';
    protected $fillable = [
        'expire_days',
        'type',
        'free_dating_feature',
        'unlimited_swipes',
        'swipe_message',
        'backtrack',
        'access_admirers',
        'status',
        'cost',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}

