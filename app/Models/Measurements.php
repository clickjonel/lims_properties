<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\Attributes\Connection;

#[Fillable([
    'name',
])]

#[Connection('lims_properties')]
#[Table(
    name: 'measurements',
    key: 'id',
    keyType: 'integer',
    incrementing: true,
    timestamps: true,
)]

class Measurements extends Model
{
    //
}
