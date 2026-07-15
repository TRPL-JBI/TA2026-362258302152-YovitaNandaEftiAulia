<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeAmi extends Model
{
    protected $table = 'periode_ami';

    protected $fillable = [
        'tahun',
        'id_standar_mutu',
        'id_unit_kerja',
        'id_user',
        'tujuan_audit',
        'lingkup_audit',
        'waktu_audit',
        'tanggal_buka_ami',
        'tanggal_tutup_ami',
        'status'
    ];

    public $timestamps = false;

    // =========================
    // RELASI STANDAR MUTU
    // =========================
    public function standarMutu()
    {
        return $this->belongsTo(
            StandarMutu::class,
            'id_standar_mutu'
        );
    }

    // =========================
    // RELASI UNIT KERJA
    // =========================
    public function unitKerja()
    {
        return $this->belongsTo(
            UnitKerja::class,
            'id_unit_kerja'
        );
    }

    // =========================
    // RELASI USER
    // =========================
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user'
        );
    }

    // =========================
    // RELASI PERIODE AMI
    // =========================
    public function standarMutuPeriode()
    {
        return $this->hasMany(
            StandarMutuPeriodeAmi::class,
            'id_periode_ami'
        );
    }

    // =========================
    // TIM AMI
    // =========================
    public function tim()
{
    return $this->hasMany(
        TimAmi::class,
        'id_periode_ami'
    );
}


// =========================
// JADWAL AMI
// =========================
public function jadwal()
{
    return $this->hasMany(
        JadwalAmi::class,
        'id_periode_ami'
    );
}

/*
|--------------------------------------------------------------------------
| KESIMPULAN AUDIT
|--------------------------------------------------------------------------
*/

public function kesimpulanAudit()
{
    return $this->hasMany(
        KesimpulanAudit::class,
        'id_periode_ami'
    );
}

/*
|--------------------------------------------------------------------------
| LAMPIRAN AUDIT
|--------------------------------------------------------------------------
*/

public function lampiran()
{
    return $this->hasMany(
        LampiranAudit::class,
        'id_periode_ami'
    );
}

}