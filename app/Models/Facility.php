<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'facilities';

    // Las columnas que se pueden asignar de manera masiva (Mass Assignment)
    protected $fillable = [
        'fac_name',
        'fac_location',
        'fac_country',
        'fac_auth',
    ];

    // Si el nombre de la columna primaria no es 'id', puedes especificarlo aquí
    protected $primaryKey = 'fac_id';

    // Si la clave primaria no es un entero (por ejemplo, si es un string), puedes especificarlo aquí
    protected $keyType = 'int';

    // Si no utilizas marcas de tiempo (created_at, updated_at), puedes desactivar el seguimiento automático
    public $timestamps = false;
}
