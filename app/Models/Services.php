<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    //
    // Nombre de la tabla asociada
    protected $table = 'services';

    // Llave primaria de la tabla
    protected $primaryKey = 'pk_service';

    // Indica si la llave primaria es auto-incremental
    public $incrementing = true;

    // Tipo de la llave primaria
    protected $keyType = 'int';

    // Desactiva los timestamps por defecto (created_at, updated_at)
    public $timestamps = false;

    // Campos asignables en masa
    protected $fillable = [
        'id_service',
        'type',
        'status',
        'transport',
        'delivery_appointment',
        'waiting_time',
        'appointment',
        'departure_date',
        'arrival_date',
        'from',
        'to',
        'date',
        'hbl',
        'containers',
        'booking',
        'bol',
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
        'spIn',
        'pedimentoR',
        'icv',
        'exchanger',
        'commissions1',
        'icvfc',
        'nicvfc',
        'dat',
        'commissions2',
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
        'ST_Date_Released',
        'dateCreated',
        'dateUpdated',
    ];

    // Define los campos que son fechas
    /*protected $dates = [
        'departure_date',
        'arrival_date',
        'date',
        'datef',
        'ST_Date_in_Transit',
        'ST_Date_Completed',
        'ST_Date_Finalized',
        'ST_Date_Invoiced',
        'ST_Date_Closed',
        'ST_Date_Released',
        'dateUpdated',
    ];*/

    // Define los formatos de atributos personalizados (opcional)
    /*protected $casts = [
        'icv' => 'decimal:2',
        'exchanger' => 'decimal:2',
        'commissions1' => 'decimal:2',
        'icvfc' => 'decimal:2',
        'dat' => 'decimal:2',
        'commissions2' => 'decimal:2',
        'wgt_kg' => 'decimal:2',
        'wgt_lbs' => 'decimal:2',
        'skids' => 'integer',
        'cartons' => 'integer',
    ];*/
    // Relación inversa con Sgipments
    public function services()
    {
        return $this->hasMany(Shipments::class, 'stm_id', 'id_service');
    }
}
