<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    // Especifica la tabla si no sigue las convenciones de Laravel
    protected $table = 'driver';

    // Define las columnas que pueden llenarse masivamente
    protected $fillable = ['pk_driver', 'id_driver', 'drivername', 'id_company'];

    // Indica que la clave primaria es `pk_driver` (si no usa `id` por defecto)
    protected $primaryKey = 'pk_driver';

    // Si la clave primaria no es auto-incremental, usa esto (si aplica)
    public $incrementing = true;

    // Si usas claves primarias no enteras (UUID, etc.), agrega esto:
    protected $keyType = 'int';
}
