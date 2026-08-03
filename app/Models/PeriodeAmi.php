<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeAmi extends Model
{
    protected $table = 'periode_ami';

    /*
    |--------------------------------------------------------------------------
    | TIMESTAMP
    |--------------------------------------------------------------------------
    |
    | Pada tabel periode_ami terdapat created_at dan updated_at.
    |
    */

    public $timestamps = true;

    protected $fillable = [
        'tahun',
        'id_standar_mutu',

        /*
        | Kolom lama tetap digunakan untuk menyimpan unit pertama.
        | Hal ini menjaga halaman lama agar tidak langsung mengalami error.
        */
        'id_unit_kerja',

        'id_user',
        'tujuan_audit',
        'lingkup_audit',
        'waktu_audit',
        'tanggal_buka_ami',
        'tanggal_tutup_ami',
        'status',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'tanggal_buka_ami' => 'date',
        'tanggal_tutup_ami' => 'date',
    ];

    public function standarMutu(): BelongsTo
    {
        return $this->belongsTo(
            StandarMutu::class,
            'id_standar_mutu'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UNIT KERJA UTAMA
    |--------------------------------------------------------------------------
    |
    | Tetap dipertahankan untuk kode lama yang memakai:
    | $periode->unitKerja
    |
    */

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(
            UnitKerja::class,
            'id_unit_kerja'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BANYAK UNIT KERJA
    |--------------------------------------------------------------------------
    */

    public function unitKerjas(): BelongsToMany
    {
        return $this->belongsToMany(
            UnitKerja::class,
            'periode_ami_unit_kerja',
            'id_periode_ami',
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
            'id_periode_ami',
            'id'
        );
    }

    public function jadwal(): HasMany
    {
        return $this->hasMany(
            JadwalAmi::class,
            'id_periode_ami'
        );
    }

    public function kesimpulanAudit(): HasMany
    {
        return $this->hasMany(
            KesimpulanAudit::class,
            'id_periode_ami'
        );
    }

    public function lampiran(): HasMany
    {
        return $this->hasMany(
            LampiranAudit::class,
            'id_periode_ami'
        );
    }
}