<?php

namespace App\Models;

use Carbon\Carbon;
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
        'offloading_time',
        'billing_id',
        'billing_date'
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

    //Convertir Fechas
    /*public function getStatusAttribute($value)
    {
        return Carbon::parse($value)->format('m/d/Y');
    }*/
    public function getPickUpDateAttribute($value)
    {
            return Carbon::parse($value)->format('m/d/Y H:i:s');
    }
    public function getIntransitDateAttribute($value)
    {
            return Carbon::parse($value)->format('m/d/Y H:i:s');
    }
    public function getDriverAssignedDateAttribute($value)
    {
            return Carbon::parse($value)->format('m/d/Y H:i:s');
    }
    public function getetdAttribute($value)
    {
            return Carbon::parse($value)->format('m/d/Y H:i:s');
    }


    // Relación con la tabla `services`
    public function services()
    {
        return $this->belongsTo(Services::class, 'stm_id', 'id_service');
    }

    // Relación con la tabla `empty_trailer`
    public function emptytrailer()
    {
        return $this->belongsTo(EmptyTrailer::class, 'id_trailer', 'trailer_num');
    }

    // Relación con la tabla `companies`
    public function origin()
    {
        return $this->belongsTo(Companies::class, 'origin', 'pk_company');
    }

    // Relación con la tabla `companies`
    public function carrier()
    {
        return $this->belongsTo(Companies::class, 'id_company', 'pk_company');
    }

    // Relación con la tabla `companies`
    public function driverowner()
    {
        return $this->belongsTo(Companies::class, 'trailer', 'pk_company');
    }

    // Relación con la tabla `driver`
    public function drivers()
    {
        return $this->belongsTo(Driver::class, 'id_driver', 'pk_driver');
    }

    // Relación con la tabla `generic_catalogs` para el estado actual
    public function currentstatus()
    {
        return $this->belongsTo(GenericCatalog::class, 'gnct_id_current_status', 'gnct_id');
    }

    // Relación con la tabla `generic_catalogs` para el tipo de envío
    public function shipmenttype()
    {
        return $this->belongsTo(GenericCatalog::class, 'gnct_id_shipment_type', 'gnct_id');
    }

    // Relación con la tabla `facilities` para el tipo de envío
    public function destinations()
    {
        return $this->belongsTo(Facilities::class, 'destination', 'fac_id');
    }


    //Formatos de Fecha de Christian y Relaciones
    // Configuración de timestamps personalizados
    const CREATED_AT = 'dateCreated';
    const UPDATED_AT = 'dateUpdated';

    // Atributos que son fechas y deben ser manejados como instancias de Carbon
    protected $dates = [
        'pre_alerted_datetime',
        'driver_assigned_date',
        'pick_up_date',
        'intransit_date',
        'secured_yarddate',
        'delivered_date',
        'at_door_date',
        'offload_date',
        'dateCreated',
        'dateUpdated',
        'wh_auth_date',
    ];

    // Formateo de la fecha de entrega
    public function getFormattedDeliveredDateAttribute()
    {
        return $this->delivered_date
            ? $this->delivered_date->format('m/d/Y H:i')
            : null;
    }

    //Relaciones
    // Relación con GenericCatalog (estatus actual)
    /*public function currentStatus()
    {
        return $this->belongsTo(GenericCatalog::class, 'gnct_id_current_status', 'gnct_id');
    }*/

    // Relación con GenericCatalog (tipo de envío)
    /*public function shipmentType()
    {
        return $this->belongsTo(GenericCatalog::class, 'gnct_id_shipment_type', 'gnct_id');
    }*/

    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'stm_id', 'pk_service');
    }

    // Método para obtener el estatus actual
    public function getCurrentStatusValueAttribute()
    {
        return $this->currentStatus ? $this->currentStatus->gntc_value : null;
    }

    // Método para obtener el tipo de envío
    public function getShipmentTypeValueAttribute()
    {
        return $this->shipmentType ? $this->shipmentType->gntc_value : null;
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'id_driver', 'pk_driver'); // Ajustado a id_driver
    }

    public function company()
    {
        return $this->belongsTo(Companies::class, 'origin', 'pk_company'); // Relación con la tabla companies
    }

    public function destinationFacility()
    {
        return $this->belongsTo(Facilities::class, 'destination', 'fac_id');
    }
}
