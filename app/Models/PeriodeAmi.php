<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeAmi extends Model
{
    protected $table = 'periode_ami';

    public $timestamps = false;

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
        'status',
    ];

    public function standarMutu(): BelongsTo
    {
        return $this->belongsTo(
            StandarMutu::class,
            'id_standar_mutu'
        );
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(
            UnitKerja::class,
            'id_unit_kerja'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_user'
        );
    }

    public function standarMutuPeriode(): HasMany
    {
        return $this->hasMany(
            StandarMutuPeriodeAmi::class,
            'id_periode_ami'
        );
    }

    public function tim(): HasMany
    {
        return $this->hasMany(
            TimAmi::class,
            'id_periode_ami'
        );
    }

    public function jadwal(): HasMany
    {
        return $this->hasMany(
            JadwalAmi::class,
            'id_periode_ami'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | KESIMPULAN PER PERIODE
    |--------------------------------------------------------------------------
    */

    public function kesimpulanAudit(): HasMany
    {
        return $this->hasMany(
            KesimpulanAudit::class,
            'id_periode_ami'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LAMPIRAN PER PERIODE
    |--------------------------------------------------------------------------
    */

    public function lampiran(): HasMany
    {
        return $this->hasMany(
            LampiranAudit::class,
            'id_periode_ami'
        );
    }
}