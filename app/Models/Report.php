<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'report';
    protected $fillable = [
        'reporting_user_id', 
        'reported_user_id', 
        'reason', 
        'status'
    ];

    public function reportingUser()
    {
        return $this->belongsTo(User::class, 'reporting_user_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'interest', 'id');
    }
    
}
