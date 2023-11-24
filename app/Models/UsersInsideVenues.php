<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersInsideVenues extends Model
{
    use HasFactory;
    protected $table = 'users_inside_venues';
	protected $fillable = ['user_id','place_id','interested_in','interested_for','incognito_mode', 'type', 'created_at', 'updated_at', "venue_name"];
	public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
