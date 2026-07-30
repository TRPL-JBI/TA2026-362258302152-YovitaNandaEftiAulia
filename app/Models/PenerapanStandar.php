<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PenerapanStandar extends Model
{
    use SoftDeletes;

    protected $table = 'penerapan_standar';

    public $timestamps = false;

    protected $fillable = [
        'id_standarmutu_periodeami',
        'id_indikator',
        'deskripsi_hasil',
        'link_bukti',
        'id_user',
    ];

    protected $casts = [
        'id_standarmutu_periodeami' => 'integer',
        'id_indikator' => 'integer',
        'id_user' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | USER / AUDITEE
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDIKATOR
    |--------------------------------------------------------------------------
    */

    public function indikator(): BelongsTo
    {
        return $this->belongsTo(
            IndikatorStandar::class,
            'id_indikator',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STANDAR MUTU PERIODE AMI
    |--------------------------------------------------------------------------
    */

    public function standarmutuPeriode(): BelongsTo
    {
        return $this->belongsTo(
            StandarMutuPeriodeAmi::class,
            'id_standarmutu_periodeami',
            'id'
        );
    }

    public function standarMutuPeriodeAmi(): BelongsTo
    {
        return $this->belongsTo(
            StandarMutuPeriodeAmi::class,
            'id_standarmutu_periodeami',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEMUAN AMI
    |--------------------------------------------------------------------------
    |
    | Satu penerapan standar dapat memiliki lebih dari satu temuan.
    |
    */

    public function temuan(): HasMany
    {
        return $this->hasMany(
            TemuanAmi::class,
            'id_penerapan_standar',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REKOMENDASI
    |--------------------------------------------------------------------------
    |
    | Rekomendasi tidak lagi terhubung langsung ke penerapan standar.
    | Relasinya melewati tabel temuan_ami.
    |
    */

    public function rekomendasi(): HasManyThrough
    {
        return $this->hasManyThrough(
            Rekomendasi::class,
            TemuanAmi::class,
            'id_penerapan_standar',
            'id_temuan',
            'id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SKOR PENERAPAN STANDAR
    |--------------------------------------------------------------------------
    */

    public function skor(): HasOne
    {
        return $this->hasOne(
            SkorPenerapanStandar::class,
            'id_penerapan_standar',
            'id'
        );
    }
}