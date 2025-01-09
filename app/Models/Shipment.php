<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    // Nombre de la tabla (si no es la convención "shipments", se define aquí)
    protected $table = 'shipments';

    // Clave primaria personalizada
    protected $primaryKey = 'pk_shipment';

    // Desactivar el incremento automático si la clave primaria no es auto-incremental
    public $incrementing = false;

    // Tipo de clave primaria si no es un entero
    protected $keyType = 'string';

    // Atributos que pueden ser asignados masivamente
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
    ];

    // Atributos que son fechas y deben ser manejados como instancias de Carbon
    protected $dates = [
        'pre_alerted_datetime',
        'driver_assigned_date',
        'pick_up_date',
        'intransit_date',
        'secured_yarddate',
        'delivered_date',
        'at_door_date',
    ];

    // Relación con GenericCatalog (estatus actual)
    public function currentStatus()
    {
        return $this->belongsTo(GenericCatalog::class, 'gnct_id_current_status', 'gnct_id');
    }

    // Relación con GenericCatalog (tipo de envío)
    public function shipmentType()
    {
        return $this->belongsTo(GenericCatalog::class, 'gnct_id_shipment_type', 'gnct_id');
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

        public function originCatalog()
    {
        return $this->belongsTo(GenericCatalog::class, 'origin', 'gnct_id'); // Relación con la tabla generic_catalogs
    }

    // Formateo de la fecha de entrega
    public function getFormattedDeliveredDateAttribute()
    {
        return $this->delivered_date
            ? $this->delivered_date->format('m/d/Y H:i')
            : null;
    }


    public $timestamps = false;
}
