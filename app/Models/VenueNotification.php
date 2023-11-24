<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueNotification extends Model
{
    use HasFactory;

    protected $table = 'venue_based_notification';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'route_name',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
