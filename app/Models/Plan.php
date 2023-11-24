<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plan';
	
	public function subscriptions()
    {
        return $this->hasMany(Subscription::class,'plan_id');
    }
}
