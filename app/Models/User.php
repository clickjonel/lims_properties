<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Attributes\Connection;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Foundation\Auth\User as Authenticatable;


#[Fillable(['name', 'username', 'password','remember_token'])]
#[Hidden(['password', 'remember_token'])]


#[Connection('lims_properties')]
#[Table(
    name: 'users',
    key: 'id',
    keyType: 'integer',
    incrementing: true,
    timestamps: true,
)]

class User extends Authenticatable
{

}
