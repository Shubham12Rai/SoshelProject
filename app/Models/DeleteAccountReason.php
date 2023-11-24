<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeleteAccountReason extends Model
{
    use HasFactory;

    protected $table = "delete_account_reason";
    
    protected $fillable = [
        "reason",
        "other_reason",
    ] ;
}
