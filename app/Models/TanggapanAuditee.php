<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TanggapanAuditee extends Model
{
    protected $table = 'tanggapan_auditee';

    public $timestamps = false;

    protected $fillable = [

        'id_temuan_ami',

        'tanggapan',

        'id_user'

    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI TEMUAN
    |--------------------------------------------------------------------------
    */

    public function temuan()
    {
        return $this->belongsTo(
            TemuanAmi::class,
            'id_temuan_ami'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI USER
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user'
        );
    }
}