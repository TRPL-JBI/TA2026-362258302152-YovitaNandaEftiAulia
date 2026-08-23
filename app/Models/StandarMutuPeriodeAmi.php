<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandarMutuPeriodeAmi extends Model
{
    protected $table = 'standarmutu_periodeami';

    public $timestamps = false;

    protected $fillable = [
        'id_standar_mutu',
        'id_periode_ami',
        'status',
    ];

    /**
     * Relasi ke Standar Mutu.
     */
    public function standarMutu()
    {
        return $this->belongsTo(
            StandarMutu::class,
            'id_standar_mutu'
        );
    }

    /**
     * Relasi ke Periode AMI.
     */
    public function periodeAmi()
    {
        return $this->belongsTo(
            PeriodeAmi::class,
            'id_periode_ami'
        );
    }

    /**
     * Relasi ke data Penerapan Standar.
     */
    public function penerapanStandar()
    {
        return $this->hasMany(
            PenerapanStandar::class,
            'id_standarmutu_periodeami'
        );
    }
}