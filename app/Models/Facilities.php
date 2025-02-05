<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facilities extends Model
{
    //
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'facilities';

    // Clave primaria de la tabla
    protected $primaryKey = 'fac_id';

    // Si la clave primaria es autoincremental
    public $incrementing = true;

    // Define el tipo de la clave primaria
    protected $keyType = 'int';

    // Desactivar timestamps automáticos si no usas `created_at` y `updated_at`
    public $timestamps = false;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'fac_name',
        'fac_location',
        'fac_country',
        'fac_auth'
    ];

    // Relación inversa con Shipments
    /*public function destinations()
    {
        return $this->hasMany(Shipments::class, 'destination', 'fac_id');
    }*/
}
