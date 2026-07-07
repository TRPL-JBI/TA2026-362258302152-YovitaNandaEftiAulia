<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [

        'nama',

        'email',

        'password',

        'id_unit_kerja',

        'status',

        'role',

    ];

    protected $hidden = [

        'password',

    ];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | RELASI UNIT KERJA
    |--------------------------------------------------------------------------
    */

    public function unit()
    {
        return $this->belongsTo(
            UnitKerja::class,
            'id_unit_kerja'
        );
    }
}