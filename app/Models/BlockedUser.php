<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    use HasFactory;

    protected $table = "blocked_users";

    protected $fillable = [
        'from',
        'to',
        'block_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'from', 'to');
    }
}
