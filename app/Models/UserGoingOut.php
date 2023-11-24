<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGoingOut extends Model
{
    use HasFactory;
    protected $table = 'user_going_out';

    protected $fillable = [
        'user_id',
        'going_out_id'
    ];

    public function going_out()
    {
        return $this->belongsTo(GoingOut::class);
    }
}
