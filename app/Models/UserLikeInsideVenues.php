<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLikeInsideVenues extends Model
{
    use HasFactory;
    protected $table = 'user_likes_inside_venues';

    public function user()
    {
        return $this->belongsTo(User::class, 'from', 'to');
    }
}
