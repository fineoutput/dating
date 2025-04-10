<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupid extends Model
{
    use HasFactory;

    protected $table = 'cupid_match';
    protected $fillable = [
        'user_id_1',
        'user_id_2',
        'maker_id',
        'accept',
        'decline',
        'message',
        'rendom',
        'identity',
        'status',
    ];

}
