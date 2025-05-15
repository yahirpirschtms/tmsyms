<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LateReason extends Model
{
    use HasFactory;

    protected $table = 'late_reasons';
    protected $primaryKey = 'pk_id';
    public $timestamps = false;

    protected $fillable = [
        'group',
        'value',
        'type',
        'subgroup',
        'status'
    ];
}
