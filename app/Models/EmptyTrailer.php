<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmptyTrailer extends Model
{
    //
    use HasFactory;

    // Define el nombre de la tabla si no sigue la convención plural de Laravel
    protected $table = 'empty_trailer';

    // Define la clave primaria si no es "id"
    protected $primaryKey = 'pk_trailer';

    // Si la clave primaria no es autoincremental
    public $incrementing = false;

    // Define el tipo de la clave primaria
    protected $keyType = 'int';

    // Indica si la tabla tiene timestamps
    public $timestamps = false;

    // Especifica los campos que pueden ser asignados en masa
    protected $fillable = [
        'trailer_num',
        'status',
        'pallets_on_trailer',
        'pallets_on_floor',
        'carrier',
        'gnct_id_avaibility_indicator',
        'location',
        'date_in',
        'date_out',
        'transaction_date',
        'username',
    ];

        // Relaciones si las hay
        public function availabilityIndicator()
        {
            return $this->belongsTo(GenericCatalog::class, 'gnct_id_avaibility_indicator', 'gnct_id');
        }
    
        public function location()
        {
            return $this->belongsTo(Companies::class, 'location', 'id_company');
        }
}
