<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinCategory extends Model
{
    protected $table = 'coincategory';
    protected $fillable = [
        'category',
        'feature',
        'bronze',
        'silver',
        'gold',
        'cost',
        'description',
        'status',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}

