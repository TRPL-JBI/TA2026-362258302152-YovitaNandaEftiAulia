<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkarMasalah extends Model
{
    protected $table = 'akar_masalah';

    public $timestamps = false;

    protected $fillable = [

        'id_temuan',

        'akar_masalah',

        'id_user'

    ];

    /*
    |--------------------------------------------------------------------------
    | TEMUAN AMI
    |--------------------------------------------------------------------------
    */

    public function temuan()
    {
        return $this->belongsTo(
            TemuanAmi::class,
            'id_temuan'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user'
        );
    }
}