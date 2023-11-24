<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPet extends Model
{
    use HasFactory;

    protected $table = 'user_pets';

    protected $fillable = [
        'user_id',
        'pet_id'
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
