<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemuanAmi extends Model
{
    /**
     * Nama tabel database.
     */
    protected $table = 'temuan_ami';

    /**
     * Tabel tidak menggunakan created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi.
     */
    protected $fillable = [
        'id_penerapan_standar',
        'temuan',
        'status_temuan',
    ];

    /**
     * Relasi utama ke Penerapan Standar.
     *
     * Nama "penerapan" dipakai oleh controller dan Blade.
     */
    public function penerapan(): BelongsTo
    {
        return $this->belongsTo(
            PenerapanStandar::class,
            'id_penerapan_standar'
        );
    }

    /**
     * Alias agar kode yang memakai nama penerapanStandar
     * tetap dapat berjalan.
     */
    public function penerapanStandar(): BelongsTo
    {
        return $this->belongsTo(
            PenerapanStandar::class,
            'id_penerapan_standar'
        );
    }

    /**
     * Tanggapan Auditee terhadap temuan.
     */
    public function tanggapan(): HasMany
    {
        return $this->hasMany(
            TanggapanAuditee::class,
            'id_temuan_ami'
        );
    }

    /**
     * Akar masalah dari temuan.
     */
    public function akarMasalah(): HasMany
    {
        return $this->hasMany(
            AkarMasalah::class,
            'id_temuan_ami'
        );
    }
}