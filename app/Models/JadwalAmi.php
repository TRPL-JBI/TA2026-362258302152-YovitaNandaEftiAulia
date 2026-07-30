<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalAmi extends Model
{
    protected $table = 'detil_jadwal_audit';

    protected $fillable = [
        'id_periode_ami',
        'kegiatan',
        'waktu',
    ];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | PERIODE AMI
    |--------------------------------------------------------------------------
    |
    | Nama relasi periodeAmi dipakai oleh controller Auditee.
    |
    */

    public function periodeAmi(): BelongsTo
    {
        return $this->belongsTo(
            PeriodeAmi::class,
            'id_periode_ami'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ALIAS RELASI LAMA
    |--------------------------------------------------------------------------
    |
    */

    public function periode(): BelongsTo
    {
        return $this->belongsTo(
            PeriodeAmi::class,
            'id_periode_ami'
        );
    }
}