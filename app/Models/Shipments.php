<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipments extends Model
{
    //
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'shipments';

    // Clave primaria de la tabla
    protected $primaryKey = 'pk_shipment';

    // Si la clave primaria es autoincremental
    public $incrementing = true;

    // Define el tipo de la clave primaria
    protected $keyType = 'int';

    // Desactivar timestamps automáticos si no usas `created_at` y `updated_at`
    public $timestamps = false;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'stm_id',
        'reference',
        'bonded',
        'origin',
        'destination',
        'pre_alerted_datetime',
        'id_trailer',
        'id_company',
        'trailer',
        'truck',
        'id_driver',
        'etd',
        'units',
        'pallets',
        'security_seals',
        'notes',
        'overhaul_id',
        'device_number',
        'secondary_shipment_id',
        'driver_assigned_date',
        'pick_up_date',
        'intransit_date',
        'secured_yarddate',
        'gnct_id_current_status',
        'gnct_id_shipment_type',
        'delivered_date',
        'at_door_date',
        'wh_auth_date',
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


    // Relación con la tabla `services`
    /*public function service()
    {
        return $this->belongsTo(Service::class, 'stm_id', 'id_service');
    }*/

    // Relación con la tabla `empty_trailer`
    public function trailer()
    {
        return $this->belongsTo(EmptyTrailer::class, 'id_trailer', 'trailer_num');
    }

    // Relación con la tabla `companies`
    public function company()
    {
        return $this->belongsTo(Companies::class, 'id_company', 'id_company');
    }

    // Relación con la tabla `driver`
    /*public function driver()
    {
        return $this->belongsTo(Driver::class, 'id_driver', 'id_driver');
    }*/

    // Relación con la tabla `generic_catalogs` para el estado actual
    public function currentStatus()
    {
        return $this->belongsTo(GenericCatalog::class, 'gnct_id_current_status', 'gnct_id');
    }

    // Relación con la tabla `generic_catalogs` para el tipo de envío
    public function shipmentType()
    {
        return $this->belongsTo(GenericCatalog::class, 'gnct_id_shipment_type', 'gnct_id');
    }
}
