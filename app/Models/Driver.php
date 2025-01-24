<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    //
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'driver';

    // Clave primaria de la tabla
    protected $primaryKey = 'pk_driver';

    // Si la clave primaria es autoincremental
    public $incrementing = true;

    // Define el tipo de la clave primaria
    protected $keyType = 'int';

    // Desactivar timestamps automáticos si no usas `created_at` y `updated_at`
    public $timestamps = false;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'id_driver',
        'drivername',
        'id_company'
    ];

    // Relación inversa con Shipments
    public function drivers()
    {
        return $this->hasMany(Shipments::class, 'id_driver', 'pk_driver');
    }
}
