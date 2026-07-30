<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkorPenerapanStandar extends Model
{
    protected $table = 'skor_penerapan_standar';

    protected $fillable = [
        'id_penerapan_standar',
        'id_skala_skor',
    ];

    protected $casts = [
        'id_penerapan_standar' => 'integer',
        'id_skala_skor' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | PENERAPAN STANDAR
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

    /*
    |--------------------------------------------------------------------------
    | SKALA SKOR
    |--------------------------------------------------------------------------
    */

    public function skalaSkor(): BelongsTo
    {
        return $this->belongsTo(
            SkalaSkor::class,
            'id_skala_skor',
            'id'
        );
    }
}