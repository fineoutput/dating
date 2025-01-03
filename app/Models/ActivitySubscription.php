<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivitySubscription extends Model
{
    use HasFactory;
    protected $table = 'activity_subscription';
    protected $fillable = [
        'interests_id',
        'title',
        'expire_days',
        'description',
        'category_id',
        'type',
        'status',
        'cost',
    ];

    public function interests()
    {
        return $this->hasOne(Interest::class ,'id','interests_id');
    }

    public function category()
    {
        return $this->hasOne(CoinCategory::class ,'id','category_id');
    }
}
