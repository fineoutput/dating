<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreDating extends Model
{
    use HasFactory;
    protected $table = 'pre_dating';
    protected $fillable = [
        'user_id', 
        'age', 
        'distance', 
        'gender',
        'status',
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
