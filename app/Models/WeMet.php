<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeMet extends Model
{
    use HasFactory;

    protected $table = 'we_met';

    protected $fillable = [
        'from_user',
        'to_user',
        'venue_base_we_met_status'
    ];
}
