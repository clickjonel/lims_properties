<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Connection;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'property_no',
    'measurement_unit',
    'particulars',
    'unit_cost',
    'status',
    'remarks',
    'category',
    'mr_to',
    'acquisition_date',
    'specs'
])]

#[Connection('lims_properties')]
#[Table(
    name: 'properties',
    key: 'id',
    keyType: 'integer',
    incrementing: true,
    timestamps: true,
)]

class Property extends Model
{
    public function personnel(){
        return $this->belongsTo(Personnel::class,'mr_to','user_id');
    }

    public function userHistory(){
        return $this->hasmany(PropertyUserHistory::class,'property_id','id');
    }

}
