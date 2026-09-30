<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Connection;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

#[Hidden(['password'])]
#[Connection('dohis')]
#[Table(
    name: 'dohis_user',
    key: 'user_id',
    keyType: 'integer',
    incrementing: true,
    timestamps: true,
)]
#[Appends(['full_name'])]
class Personnel extends Model
{
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim(
                $this->first_name
                . ($this->middle_name ? ' ' . strtoupper($this->middle_name[0]) . '.' : '')
                . ' ' . $this->last_name
            ),
        );
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'mr_to', 'user_id');
    }

    public function personnelAssignment(){
        return $this->hasOne(PersonnelAssignment::class,'user_id','user_id');
    }


}