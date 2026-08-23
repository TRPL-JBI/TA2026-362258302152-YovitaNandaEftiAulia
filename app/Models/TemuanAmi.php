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

        // Verifikasi formal
        'verified_by',
        'verified_at',
        'closed_by',
        'closed_at',
        'verification_note',
    ];

    protected $casts = [
        'id_penerapan_standar' => 'integer',
        'verified_by' => 'integer',
        'closed_by' => 'integer',

        'verified_at' => 'datetime',
        'closed_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi Penerapan Standar
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
    | Relasi Rekomendasi
    |--------------------------------------------------------------------------
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
    | Relasi Tanggapan Auditee
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
    | Relasi Akar Masalah
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
    | Relasi User Verifikator
    |--------------------------------------------------------------------------
    */

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi User Penutup
    |--------------------------------------------------------------------------
    */

    public function closer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'closed_by',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Status Verifikasi
    |--------------------------------------------------------------------------
    */

    public function sudahDiverifikasi(): bool
    {
        return !is_null($this->verified_at);
    }

    public function sudahDitutup(): bool
    {
        return $this->status_temuan === 'closed';
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Jenis Temuan
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
            [
                'kts',
                'ob',
            ],
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Label Jenis Temuan
    |--------------------------------------------------------------------------
    */

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