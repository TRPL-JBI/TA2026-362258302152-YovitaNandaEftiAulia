<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AkarMasalah extends Model
{
    protected $table = 'akar_masalah';

    public $timestamps = false;

    protected $fillable = [
        'id_temuan',
        'akar_masalah',
        'id_user',
    ];

    protected $casts = [
        'id_temuan' => 'integer',
        'id_user' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | TEMUAN AMI
    |--------------------------------------------------------------------------
    */

    public function temuan(): BelongsTo
    {
        return $this->belongsTo(
            TemuanAmi::class,
            'id_temuan',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | USER
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
}