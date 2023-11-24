<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueFeedback extends Model
{
    use HasFactory;
    protected $table = 'venue_feedback';

    protected $fillable = [
        'place_id',
        'user_id',
        'status',
        'feedback_reason',
        'place_name',
        'address'
    ];

    public function users()
    {
        // Assuming you have a 'users' table and a foreign key in VenueFeedback
        return $this->belongsTo(User::class, 'user_id');
    }

    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedback_reason');
    }
}