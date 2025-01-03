<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    protected $table = 'coin';
    protected $fillable = [
        'user_id',
        'coins',
        'date'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}

