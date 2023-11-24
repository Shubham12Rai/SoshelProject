<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plan;
use App\Models\User;

class Subscription extends Model
{
    use HasFactory;
	
	protected $table = 'subscriptions';	

	public function user()
    {
        return $this->belongsTo(User::class,'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class,'plan_id');
    }
}
