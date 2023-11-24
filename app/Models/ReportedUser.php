<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedUser extends Model
{
    use HasFactory;

    protected $table = 'reported_users';

    protected $fillable = [
        'from',
        'to',
        'reasons_id',
        'other_reason'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'from', 'to');
    }
}