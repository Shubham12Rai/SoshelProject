<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
    use HasFactory;

    protected $table = "fcm_token";

    protected $fillable = [
        'user_id',
        'token',
        'device_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
