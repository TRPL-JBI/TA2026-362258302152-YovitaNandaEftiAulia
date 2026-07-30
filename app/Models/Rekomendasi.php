<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rekomendasi extends Model
{
    use SoftDeletes;

    protected $table = 'rekomendasi';

    public $timestamps = false;

    protected $fillable = [
        'id_temuan',
        'aspek',
        'deskripsi',
        'rekomendasi',
        'id_user',
    ];

    protected $casts = [
        'id_temuan' => 'integer',
        'id_user' => 'integer',
        'deleted_at' => 'datetime',
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
    | USER / AUDITOR
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