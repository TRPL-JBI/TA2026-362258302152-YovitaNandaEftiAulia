<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AkarMasalah extends Model
{
    use SoftDeletes;

    protected $table = 'akar_masalah';

    public $timestamps = false;

    protected $fillable = [
        'id_temuan',
        'akar_masalah',
        'id_user',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function temuan(): BelongsTo
    {
        return $this->belongsTo(
            TemuanAmi::class,
            'id_temuan'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_user'
        );
    }
}