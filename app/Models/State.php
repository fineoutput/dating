<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'all_states';
    protected $fillable = ['state_name'];
    
    public function cities()
    {
        return $this->hasMany(Citys::class);
    }
}

