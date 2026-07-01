<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalAmi extends Model
{
    protected $table = 'detil_jadwal_audit';

    protected $fillable = [
        'id_periode_ami',
        'kegiatan',
        'waktu'
    ];

    public $timestamps = false;

    // =========================
    // RELASI PERIODE AMI
    // =========================
    public function periode()
    {
        return $this->belongsTo(
            PeriodeAmi::class,
            'id_periode_ami'
        );
    }
}