<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactiondetail extends Model
{
    use HasFactory;
    protected $table = 'transaction_details';

    protected $fillable = [
        'user_id',
    ];
}