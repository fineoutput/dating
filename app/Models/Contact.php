<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
        protected $table = 'contact_request';
        protected $fillable = [
            'user_id',  
            'number',  
            'status', 
        ];


        public function vibe()
        {
            return $this->belongsTo(Vibes::class, 'vibe_id');
        }
        public function admincity()
        {
            return $this->hasOne(AdminCity::class, 'id','admin_city');
        }
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
        
}
