<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenerapanStandar extends Model
{
    /**
     * Nama tabel database.
     */
    protected $table = 'penerapan_standar';

    /**
     * Tabel tidak menggunakan created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi.
     */
    protected $fillable = [
        'id_standarmutu_periodeami',
        'id_indikator',
        'deskripsi_hasil',
        'link_bukti',
        'id_user',
    ];

    /**
     * Auditee yang mengisi penerapan standar.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_user'
        );
    }

    /**
     * Indikator yang diterapkan oleh Auditee.
     */
    public function indikator(): BelongsTo
    {
        return $this->belongsTo(
            IndikatorStandar::class,
            'id_indikator'
        );
    }

    /**
     * Hubungan penerapan dengan standar mutu pada periode AMI.
     */
    public function standarmutuPeriode(): BelongsTo
    {
        return $this->belongsTo(
            StandarMutuPeriodeAmi::class,
            'id_standarmutu_periodeami'
        );
    }

    /**
     * Alias relasi agar kode lama yang masih menggunakan
     * standarMutuPeriodeAmi tetap dapat berjalan.
     */
    public function standarMutuPeriodeAmi(): BelongsTo
    {
        return $this->belongsTo(
            StandarMutuPeriodeAmi::class,
            'id_standarmutu_periodeami'
        );
    }

    /**
     * Temuan yang dibuat Auditor berdasarkan penerapan ini.
     */
    public function temuan(): HasMany
    {
        return $this->hasMany(
            TemuanAmi::class,
            'id_penerapan_standar'
        );
    }

    /**
     * Rekomendasi peningkatan untuk penerapan standar.
     */
    public function rekomendasi(): HasMany
    {
        return $this->hasMany(
            RekomendasiPeningkatan::class,
            'id_penerapan_standar'
        );
    }
}