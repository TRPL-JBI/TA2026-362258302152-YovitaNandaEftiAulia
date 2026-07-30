<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TemuanAmi extends Model
{
    use SoftDeletes;

    protected $table = 'temuan_ami';

    public $timestamps = false;

    protected $fillable = [
        'id_penerapan_standar',
        'jenis_temuan',
        'temuan',
        'status_temuan',
    ];

    protected $casts = [
        'id_penerapan_standar' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | PENERAPAN STANDAR
    |--------------------------------------------------------------------------
    */

    public function penerapanStandar(): BelongsTo
    {
        return $this->belongsTo(
            PenerapanStandar::class,
            'id_penerapan_standar',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ALIAS PENERAPAN
    |--------------------------------------------------------------------------
    |
    | Alias ini dipertahankan agar kode lama yang memakai
    | $temuan->penerapan tetap dapat digunakan.
    |
    */

    public function penerapan(): BelongsTo
    {
        return $this->belongsTo(
            PenerapanStandar::class,
            'id_penerapan_standar',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REKOMENDASI
    |--------------------------------------------------------------------------
    |
    | Satu data temuan memiliki satu rekomendasi.
    | Rekomendasi dapat berupa rekomendasi peningkatan
    | maupun rekomendasi perbaikan.
    |
    */

    public function rekomendasi(): HasOne
    {
        return $this->hasOne(
            Rekomendasi::class,
            'id_temuan',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TANGGAPAN AUDITEE
    |--------------------------------------------------------------------------
    */

    public function tanggapan(): HasMany
    {
        return $this->hasMany(
            TanggapanAuditee::class,
            'id_temuan_ami',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AKAR MASALAH
    |--------------------------------------------------------------------------
    */

    public function akarMasalah(): HasMany
    {
        return $this->hasMany(
            AkarMasalah::class,
            'id_temuan',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER JENIS TEMUAN
    |--------------------------------------------------------------------------
    */

    public function sudahSesuaiStandar(): bool
    {
        return $this->jenis_temuan === 'sesuai_standar';
    }

    public function merupakanKts(): bool
    {
        return $this->jenis_temuan === 'kts';
    }

    public function merupakanOb(): bool
    {
        return $this->jenis_temuan === 'ob';
    }

    public function membutuhkanPerbaikan(): bool
    {
        return in_array(
            $this->jenis_temuan,
            ['kts', 'ob'],
            true
        );
    }

    public function getLabelJenisTemuanAttribute(): string
    {
        return match ($this->jenis_temuan) {
            'sesuai_standar' => 'Sudah Sesuai Standar',
            'kts' => 'KTS',
            'ob' => 'OB',
            default => '-',
        };
    }
}