<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';

    protected $fillable = [
        'sender_id',
        'reciever_id',
        'message',
        'message_from'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id', 'reciever_id');
    }
}
