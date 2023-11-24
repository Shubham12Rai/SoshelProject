<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sport;


class UserSport extends Model
{
    use HasFactory;

    protected $table = 'user_sports';

    protected $fillable = [
        'user_id',
        'sport_id',
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
}
