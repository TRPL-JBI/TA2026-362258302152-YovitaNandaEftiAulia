<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekomendasiPeningkatan extends Model
{
    protected $table = 'rekomendasi_peningkatan';

    public $timestamps = false;

    protected $fillable = [

        'id_penerapan_standar',

        'aspek',

        'kelebihan',

        'rekomendasi',

        'id_user'

    ];

    /*
    |--------------------------------------------------------------------------
    | PENERAPAN STANDAR
    |--------------------------------------------------------------------------
    */

    public function penerapan()
    {
        return $this->belongsTo(
            PenerapanStandar::class,
            'id_penerapan_standar'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ALIAS RELASI LAMA
    |--------------------------------------------------------------------------
    */

    public function penerapanStandar()
    {
        return $this->belongsTo(
            PenerapanStandar::class,
            'id_penerapan_standar'
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