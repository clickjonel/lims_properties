<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\Attributes\Connection;

#[Fillable([
    'property_id',
    'mr_to',
    'issuance_date',
    'return_date',
])]

#[Connection('lims_properties')]
#[Table(
    name: 'property_user_histories',
    key: 'id',
    keyType: 'integer',
    incrementing: true,
    timestamps: true,
)]
class PropertyUserHistory extends Model
{
    //
}
