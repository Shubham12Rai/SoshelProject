<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $table = 'likes';

    protected $fillable = [
        'user_id',
        'liked_user_id',
        'like_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'liked_user_id');
    }
}
