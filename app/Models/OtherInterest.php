<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherInterest extends Model
{
    use HasFactory;
    protected $table = 'other_interest';
    protected $fillable = [
        'user_id', 
        'activity_id', 
        'user_id_1', 
        'confirm',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'interest', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
    
}
