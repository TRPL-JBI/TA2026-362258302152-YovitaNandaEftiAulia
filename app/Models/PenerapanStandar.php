<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerapanStandar extends Model
{
    protected $table = 'penerapan_standar';

    public $timestamps = false;

    protected $fillable = [
        'id_standarmutu_periodeami',
        'id_indikator',
        'deskripsi_hasil',
        'link_bukti',
        'id_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function standarmutuPeriode()
    {
        return $this->belongsTo(
            StandarMutuPeriodeAmi::class,
            'id_standarmutu_periodeami'
        );
    }

    public function standarMutuPeriodeAmi()
    {
        return $this->standarmutuPeriode();
    }

    public function indikator()
    {
        return $this->belongsTo(IndikatorStandar::class, 'id_indikator');
    }

    public function pertanyaan()
    {
        return $this->hasMany(PertanyaanAmi::class, 'id_penerapan_standar');
    }

    public function rekomendasi()
    {
        return $this->hasMany(
            RekomendasiPeningkatan::class,
            'id_penerapan_standar'
        );
    }
}