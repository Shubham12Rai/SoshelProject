<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventJoinStatus extends Model
{
	use HasFactory;
	protected $table = 'event_join_status';

	protected $fillable = [
		'event_id',
		'user_id',
		'join_status'
	];
}
