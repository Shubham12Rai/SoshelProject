<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueDrinkRequest extends Model
{
    use HasFactory;

    protected $table = 'venue_drink_request';

    protected $fillable = [
        'sender_user_id',
        'receiver_user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_user_id', 'receiver_user_id');
    }
}
