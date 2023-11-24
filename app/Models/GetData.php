<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class GetData extends Model
{
    use HasFactory;

    protected $table = 'get_data';

    protected $fillable = [
        'data'
    ];
}
