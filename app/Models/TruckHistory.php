<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TruckHistory extends Model
{
    protected $table = 'truck_history';
    public $timestamps = false; // No se necesitan created_at ni updated_at

    protected $fillable = [
        'id_shipment',
        'truck_number',
        'status',
        'transaction_date'
    ];
}
