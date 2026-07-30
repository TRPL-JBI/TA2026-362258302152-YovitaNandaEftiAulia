<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimAmi extends Model
{
    protected $table = 'tim_ami';

    protected $fillable = [
        'id_periode_ami',
        'id_user',
        'role',
    ];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_user'
        );
    }

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
    | Dipertahankan agar kode lama yang masih memakai periode()
    | tetap berjalan.
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