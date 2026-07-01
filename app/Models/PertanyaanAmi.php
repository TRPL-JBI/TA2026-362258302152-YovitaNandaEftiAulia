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

    public function penerapanStandar()
    {
        return $this->belongsTo(
            PenerapanStandar::class,
            'id_penerapan_standar'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user'
        );
    }

    public function pertanyaan()
    {
        return $this->hasMany(
            PertanyaanAmi::class,
            'id_penerapan_standar'
        );
    }
}