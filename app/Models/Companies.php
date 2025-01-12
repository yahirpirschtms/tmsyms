<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    //
    protected $table = 'companies'; // Nombre de la tabla

    protected $primaryKey = 'pk_company';

    // Deshabilitar timestamps si no tienes columnas `created_at` y `updated_at`
    public $timestamps = false;

    use HasFactory;

    // Campos permitidos para asignación masiva
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
        'mf_customer_number',
        'sub_class',
        'qb_name',
        'stCOT',
        'dateCreated',
        'dateUpdated',
    ];

    //Esto desactiva la protección contra asignación masiva, pero se debe usar con precaución.
    protected $guarded = [];

    // Relación inversa con EmptyTrailer
    public function trailers()
    {
        return $this->hasMany(EmptyTrailer::class, 'location', 'id_company');
    }

}
