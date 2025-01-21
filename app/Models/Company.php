<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    // Nombre de la tabla
    protected $table = 'companies';

    // Clave primaria de la tabla
    protected $primaryKey = 'pk_company';

    // Deshabilitar incrementos automáticos si la clave primaria no es autoincremental
    public $incrementing = false;

    // Tipo de clave primaria (si no es un entero, como UUID o string)
    protected $keyType = 'string';

    // Campos asignables en masa (fillable)
    protected $fillable = [
        'id_company',
        'CoName',
        'Shipper',
        'Client',
        'Consignee',
        'Notes',
        'Vendor',
        'code',
        'agent',
        'mf_costumer_number',
        'sub_class',
        'qb_name',
        'stCOT',
        'dateCreated',
        'dateUpdated'
    ];

    // Campos que deben ser tratados como fechas
    protected $dates = [
        'dateCreated',
        'dateUpdated'
    ];

    // Opcional: Si necesitas definir valores predeterminados
    protected $attributes = [
        'Notes' => '',
        'Client' => false, // Si es boolean
        // Agregar más valores predeterminados si es necesario
    ];
}
