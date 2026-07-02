<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemuanAmi extends Model
{
    protected $table = 'temuan_ami';

    public $timestamps = false;

    protected $fillable = [

        'id_pertanyaan',

        'temuan',

        'status_temuan'

    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi ke Pertanyaan
    |--------------------------------------------------------------------------
    */

    public function pertanyaan()
    {
        return $this->belongsTo(
            PertanyaanAmi::class,
            'id_pertanyaan'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi ke Tanggapan Auditee
    |--------------------------------------------------------------------------
    */

    public function tanggapan()
    {
        return $this->hasMany(
            TanggapanAuditee::class,
            'id_temuan_ami'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi ke Akar Masalah
    |--------------------------------------------------------------------------
    */

    public function akarMasalah()
    {
        return $this->hasMany(
            AkarMasalah::class,
            'id_temuan'
        );
    }

}