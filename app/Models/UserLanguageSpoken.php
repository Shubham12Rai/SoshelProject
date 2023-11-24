<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLanguageSpoken extends Model
{
    use HasFactory;

    protected $table = 'user_language_spoken';

    protected $fillable = [
        'user_id',
        'language_id'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
