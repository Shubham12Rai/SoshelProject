<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceGoogleDataSave extends Model
{
    protected $fillable = [
        'place_name', 'place_id', 'vicinity',
        'latitude', 'longitude', 'json_object',
        'service_type_id', 'service_city_id'
    ];

    protected $table = 'service_google_data_save';
    use HasFactory;

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }
}
