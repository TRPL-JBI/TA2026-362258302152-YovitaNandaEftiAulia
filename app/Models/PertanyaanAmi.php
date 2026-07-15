<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PertanyaanAmi extends Model
{
    protected $table = 'pertanyaan_ami';

    protected $fillable = [

        'pertanyaan',

        'indikator',

        'referensi',

        'id_penerapan_standar',

        'id_user'

    ];

    public $timestamps = false;

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

    /*
    |--------------------------------------------------------------------------
    | RELASI BARU
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
    | TEMUAN
    |--------------------------------------------------------------------------
    */

    public function temuan()
    {
        return $this->hasMany(
            TemuanAmi::class,
            'id_pertanyaan'
        );
    }

    /*
|--------------------------------------------------------------------------
| REKOMENDASI
|--------------------------------------------------------------------------
*/

public function rekomendasi()
{
    return $this->hasMany(
        RekomendasiPeningkatan::class,
        'id_penerapan_standar'
    );
}

}