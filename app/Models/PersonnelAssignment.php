<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Connection;


#[Hidden(['password'])]
#[Connection('dohis')]
#[Table(
    name: 'dohis_user_assignment',
    key: 'user_assignment_id',
    keyType: 'integer',
    incrementing: true,
    timestamps: true,
)]
#[Appends(['full_name'])]
class PersonnelAssignment extends Model
{
    
}
