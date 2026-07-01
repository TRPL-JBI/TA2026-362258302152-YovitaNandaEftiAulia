<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerapanStandar extends Model
{
    protected $table = 'penerapan_standar';

    public $timestamps = false;

    protected $fillable = [
        'id_standarmutu_periodeami',
        'deskripsi_hasil',
        'link_bukti',
        'id_user'
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user'
        );
    }

    public function standarMutuPeriodeAmi()
    {
        return $this->belongsTo(
            StandarMutuPeriodeAmi::class,
            'id_standarmutu_periodeami'
        );
    }

    public function pertanyaanAmi()
    {
        return $this->hasMany(
            PertanyaanAmi::class,
            'id_penerapan_standar'
        );
    }
}