<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericCatalog extends Model
{
    //
    use HasFactory;

    protected $table = 'generic_catalogs'; // Nombre de la tabla

    protected $primaryKey = 'gnct_id';

    // Deshabilitar timestamps si no tienes columnas `created_at` y `updated_at`
    public $timestamps = false;

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'gntc_value',
        'gntc_gntc_id',
        'gntc_description',
        'gntc_group',
        'gntc_status',
        'gntc_creation_date',
        'gntc_user',
        'gntc_update_date',
        'gntc_update_user',
        'gntc_label',
    ];

    //Esto desactiva la protección contra asignación masiva, pero se debe usar con precaución.
    protected $guarded = [];

        // Relación inversa con EmptyTrailer
        public function availabilityIndicator()
        {
            return $this->hasMany(EmptyTrailer::class, 'gnct_id_availability_indicator', 'gnct_id');
        }

        // Relación inversa con Shipments
        public function currentstatus()
        {
            return $this->hasMany(Shipments::class, 'gnct_id_current_status', 'gnct_id');
        }

         // Relación inversa con Shipments
         public function shipmenttype()
         {
             return $this->hasMany(Shipments::class, 'gnct_id_shipment_type', 'gnct_id');
         }
    
}
