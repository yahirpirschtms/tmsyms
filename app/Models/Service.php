<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla si es diferente a la forma plural del nombre del modelo
    protected $table = 'services';

    // Especificar las columnas que se pueden llenar masivamente (mass assignable)
    protected $fillable = [
        'pk_service',
        'id_service',
        'type',
        'status',
        'transport',
        'delivery_appoinment',
        'waiting_time',
        'appoinment',
        'departure_date',
        'arrival_date',
        'from',
        'to',
        'date',
        'hbl',
        'mbl',
        'vesselname',
        'awb',
        'datef',
        'timef',
        'receivingp',
        'trackingn',
        'flight',
        'name',
        'class',
        'spin',
        'pedimentoR',
        'icv',
        'exchanger',
        'commissions1',
        'crn',
        'trnu',
        'ST_Date_in_Transit',
        'ST_Date_Completed',
        'ST_Date_Finalized',
        'ST_Date_Invoiced',
        'ST_Date_Closed',
        'id_company',
        'tms_reference',
        'wgt_kg',
        'wgt_lbs',
        'skids',
        'carrier2',
        'cartons',
        'wh_doc',
        'ci_doc',
        'other_doc',
        'ST_Date_released',
        'dateCreated',
        'dateUpdated',
    ];

    // Si no estás usando los timestamps (created_at y updated_at), puedes desactivar con:
    public $timestamps = false;
}
