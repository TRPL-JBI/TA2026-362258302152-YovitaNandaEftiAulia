<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AkarMasalah extends Model
{
    use SoftDeletes;

    protected $table = 'akar_masalah';

    protected $fillable = [
        'id_temuan',
        'id_user',
        'deskripsi',
    ];

    protected $casts = [
        'id_temuan' => 'integer',
        'id_user' => 'integer',
        'deleted_at' => 'datetime',
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
            'id_temuan'
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