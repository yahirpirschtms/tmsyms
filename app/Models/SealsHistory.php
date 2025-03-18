<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SealsHistory extends Model
{
    protected $table = 'seals_history';

    // Deshabilitar timestamps si no son necesarios
    public $timestamps = false;

    // Definir campos que se pueden asignar de forma masiva
    protected $fillable = ['id_shipment', 'seal_num', 'status', 'transaction_date'];
}
