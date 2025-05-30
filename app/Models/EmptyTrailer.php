<?php

namespace App\Models;

use Carbon\Carbon;
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
    public $incrementing = true;

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
        'gnct_id_availability_indicator',
        'location',
        'date_in',
        'date_out',
        'transaction_date',
        'username',
        'availability'
    ];

        protected static function boot(){
            parent::boot();

            // Evento para cuando se crea un nuevo registro
            static::creating(function ($model) {
                $model->dateCreated = now();
                $model->dateUpdated = now();
            });

            // Evento para cuando se actualiza un registro
            static::updating(function ($model) {
                $model->dateUpdated = now();
            });
        }

        // Relaciones si las hay
        public function availabilityIndicator()
        {
            return $this->belongsTo(GenericCatalog::class, 'gnct_id_availability_indicator', 'gnct_id');
        }
    
        public function locations()
        {
            return $this->belongsTo(Companies::class, 'location', 'pk_company');
        }

        public function carriers()
        {
            return $this->belongsTo(Companies::class, 'carrier', 'pk_company');
        }

        // Relación inversa con Shipments
        /*public function emptytrailer()
        {
            return $this->hasMany(Shipments::class, 'id_trailer', 'trailer_num');
        }*/


        //Convertir Fechas
            public function getStatusAttribute($value)
        {
            return Carbon::parse($value)->format('m/d/Y');
        }

        public function getDateInAttribute($value)
        {
            return Carbon::parse($value)->format('m/d/Y H:i:s');
        }

        public function getDateOutAttribute($value)
        {
            return $value ? Carbon::parse($value)->format('m/d/Y H:i:s') : null;
        }

        public function getTransactionDateAttribute($value)
        {
            return $value ? Carbon::parse($value)->format('m/d/Y H:i:s') : null;
        }

}
