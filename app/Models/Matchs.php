<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matchs extends Model
{
    use HasFactory;

    protected $table = 'match';

    protected $fillable = [
        'sender_user_id',
        'receiver_user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_user_id', 'receiver_user_id');
    }
}
